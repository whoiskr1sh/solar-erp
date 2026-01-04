<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['project', 'assignedTo', 'creator', 'approver']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('activity_code', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhereHas('project', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority') && $request->input('priority') !== 'all') {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('project_id') && $request->input('project_id') !== 'all') {
            $query->where('project_id', $request->input('project_id'));
        }

        if ($request->filled('assigned_to') && $request->input('assigned_to') !== 'all') {
            $query->where('assigned_to', $request->input('assigned_to'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('planned_start_date', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        if ($request->filled('overdue') && $request->input('overdue') === 'true') {
            $query->overdue();
        }

        if ($request->filled('milestones') && $request->input('milestones') === 'true') {
            $query->milestones();
        }

        $activities = $query->orderBy('planned_start_date', 'asc')->paginate(20);

        // Get stats
        $stats = [
            'total' => Activity::count(),
            'planned' => Activity::planned()->count(),
            'in_progress' => Activity::inProgress()->count(),
            'completed' => Activity::completed()->count(),
            'on_hold' => Activity::onHold()->count(),
            'overdue' => Activity::overdue()->count(),
            'milestones' => Activity::milestones()->count(),
            'high_priority' => Activity::highPriority()->count(),
        ];

        // Get filter options
        $users = User::select('id', 'name')->get();
        $projects = Project::select('id', 'name')->get();

        return view('activities.index', compact('activities', 'stats', 'users', 'projects'));
    }

    public function create()
    {
        $users = User::select('id', 'name')->get();
        $projects = Project::select('id', 'name')->get();

        return view('activities.create', compact('users', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:task,milestone,meeting,delivery,review,other',
            'priority' => 'required|in:low,medium,high,critical',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'planned_start_date' => 'nullable|date|after_or_equal:today',
            'planned_end_date' => 'nullable|date|after:planned_start_date',
            'estimated_hours' => 'nullable|integer|min:0',
            'estimated_cost' => 'nullable|numeric|min:0',
            'deliverables' => 'nullable|string',
            'acceptance_criteria' => 'nullable|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_milestone' => 'boolean',
            'is_billable' => 'boolean',
        ]);

        $activity = Activity::create([
            'activity_code' => Activity::generateActivityCode(),
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority,
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
            'planned_start_date' => $request->planned_start_date,
            'planned_end_date' => $request->planned_end_date,
            'estimated_hours' => $request->estimated_hours ?? 0,
            'estimated_cost' => $request->estimated_cost ?? 0,
            'deliverables' => $request->deliverables,
            'acceptance_criteria' => $request->acceptance_criteria,
            'notes' => $request->notes,
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'is_milestone' => $request->boolean('is_milestone'),
            'is_billable' => $request->boolean('is_billable', true),
        ]);

        return redirect()->route('activities.show', $activity)->with('success', 'Activity created successfully!');
    }

    public function show(Activity $activity)
    {
        $activity->load(['project', 'assignedTo', 'creator', 'approver']);
        
        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $users = User::select('id', 'name')->get();
        $projects = Project::select('id', 'name')->get();

        return view('activities.edit', compact('activity', 'users', 'projects'));
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:task,milestone,meeting,delivery,review,other',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:planned,in_progress,completed,on_hold,cancelled',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date|after:planned_start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after:actual_start_date',
            'estimated_hours' => 'nullable|integer|min:0',
            'actual_hours' => 'nullable|integer|min:0',
            'progress_percentage' => 'nullable|numeric|min:0|max:100',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'progress_notes' => 'nullable|string',
            'completion_notes' => 'nullable|string',
            'deliverables' => 'nullable|string',
            'acceptance_criteria' => 'nullable|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_milestone' => 'boolean',
            'is_billable' => 'boolean',
        ]);

        $updateData = $request->except(['tags']);
        $updateData['tags'] = $request->tags ? explode(',', $request->tags) : null;
        $updateData['is_milestone'] = $request->boolean('is_milestone');
        $updateData['is_billable'] = $request->boolean('is_billable');

        // Handle status changes
        if ($request->status === 'in_progress' && $activity->status !== 'in_progress') {
            $updateData['actual_start_date'] = $updateData['actual_start_date'] ?: now();
        }

        if ($request->status === 'completed' && $activity->status !== 'completed') {
            $updateData['actual_end_date'] = $updateData['actual_end_date'] ?: now();
            $updateData['progress_percentage'] = 100;
        }

        $activity->update($updateData);

        return redirect()->route('activities.show', $activity)->with('success', 'Activity updated successfully!');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('activities.index')->with('success', 'Activity deleted successfully!');
    }

    // Status management methods
    public function markInProgress(Activity $activity)
    {
        $activity->markAsInProgress();
        return back()->with('success', 'Activity marked as in progress!');
    }

    public function markCompleted(Request $request, Activity $activity)
    {
        $request->validate([
            'completion_notes' => 'required|string',
        ]);

        $activity->markAsCompleted($request->completion_notes);
        return back()->with('success', 'Activity marked as completed!');
    }

    public function markOnHold(Request $request, Activity $activity)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $activity->markAsOnHold($request->notes);
        return back()->with('success', 'Activity marked as on hold!');
    }

    public function markCancelled(Request $request, Activity $activity)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $activity->markAsCancelled($request->notes);
        return back()->with('success', 'Activity marked as cancelled!');
    }

    public function updateProgress(Request $request, Activity $activity)
    {
        $request->validate([
            'progress_percentage' => 'required|numeric|min:0|max:100',
            'progress_notes' => 'nullable|string',
        ]);

        $activity->updateProgress($request->progress_percentage, $request->progress_notes);
        return back()->with('success', 'Progress updated successfully!');
    }

    public function addHours(Request $request, Activity $activity)
    {
        $request->validate([
            'hours' => 'required|integer|min:1',
        ]);

        $activity->addActualHours($request->hours);
        return back()->with('success', 'Hours added successfully!');
    }

    public function addCost(Request $request, Activity $activity)
    {
        $request->validate([
            'cost' => 'required|numeric|min:0',
        ]);

        $activity->addActualCost($request->cost);
        return back()->with('success', 'Cost added successfully!');
    }

    public function approve(Activity $activity)
    {
        $activity->approve(Auth::user());
        return back()->with('success', 'Activity approved successfully!');
    }

    public function export(Request $request)
    {
        $query = Activity::with(['project', 'assignedTo', 'creator']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('activity_code', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhereHas('project', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority') && $request->input('priority') !== 'all') {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('project_id') && $request->input('project_id') !== 'all') {
            $query->where('project_id', $request->input('project_id'));
        }

        if ($request->filled('assigned_to') && $request->input('assigned_to') !== 'all') {
            $query->where('assigned_to', $request->input('assigned_to'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('planned_start_date', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        if ($request->filled('overdue') && $request->input('overdue') === 'true') {
            $query->overdue();
        }

        if ($request->filled('milestones') && $request->input('milestones') === 'true') {
            $query->milestones();
        }

        $activities = $query->orderBy('planned_start_date', 'asc')->get();

        $filename = 'activities_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Activity Code', 'Title', 'Type', 'Priority', 'Status', 'Project',
                'Assigned To', 'Planned Start', 'Planned End', 'Progress %', 'Estimated Hours', 'Actual Hours', 'Created At'
            ]);

            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->activity_code,
                    $activity->title,
                    ucfirst($activity->type),
                    ucfirst($activity->priority),
                    ucfirst($activity->status),
                    $activity->project->name ?? 'N/A',
                    $activity->assignedTo->name ?? 'Unassigned',
                    $activity->planned_start_date ? $activity->planned_start_date->format('Y-m-d H:i') : 'Not set',
                    $activity->planned_end_date ? $activity->planned_end_date->format('Y-m-d H:i') : 'Not set',
                    $activity->progress_percentage . '%',
                    $activity->estimated_hours,
                    $activity->actual_hours,
                    $activity->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
