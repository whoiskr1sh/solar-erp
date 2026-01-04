<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Lead;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Traits\HandlesDeletionApproval;

class ProjectController extends Controller
{
    use HandlesDeletionApproval;
    public function index(Request $request)
    {
        $query = Project::with(['client', 'projectManager', 'projectEngineer', 'liaisoningOfficer', 'creator']);

        // Filters
        if ($request->filled('status')) {
            if ($request->status === 'in_progress') {
                // Filter projects that have tasks in progress
                $query->whereHas('tasks', function($q) {
                    $q->where('status', 'in_progress');
                });
            } else {
            $query->where('status', $request->status);
            }
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('project_manager_id')) {
            $query->where('project_manager_id', $request->project_manager_id);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('project_code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->latest()->paginate(15);
        $users = User::where('is_active', true)->get();
        
        $stats = [
            'total' => Project::count(),
            'planning' => Project::where('status', 'planning')->count(),
            'active' => Project::where('status', 'active')->count(),
            'in_progress' => Project::whereHas('tasks', function($q) {
                $q->where('status', 'in_progress');
            })->count(), // Projects with tasks in progress
            'on_hold' => Project::where('status', 'on_hold')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'total_budget' => Project::sum('budget'),
            'total_cost' => Project::sum('actual_cost'),
        ];

        return view('projects.index', compact('projects', 'users', 'stats'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        $clients = Lead::where('status', 'converted')->get();
        return view('projects.create', compact('users', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_code' => 'required|string|max:50|unique:projects',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'project_manager_id' => 'nullable|exists:users,id',
            'project_engineer' => 'nullable|exists:users,id',
            'liaisoning_officer' => 'nullable|exists:users,id',
            'client_id' => 'nullable|exists:leads,id',
            'location' => 'nullable|string',
            'milestones' => 'nullable|array',
        ]);

        Project::create([
            ...$request->all(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'projectManager', 'projectEngineer', 'liaisoningOfficer', 'creator', 'tasks.assignedTo', 'invoices']);
        $tasks = $project->tasks()->with('assignedTo')->get();
        $users = User::where('is_active', true)->get();
        
        return view('projects.show', compact('project', 'tasks', 'users'));
    }

    public function edit(Project $project)
    {
        $users = User::where('is_active', true)->get();
        $clients = Lead::where('status', 'converted')->get();
        return view('projects.edit', compact('project', 'users', 'clients'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_code' => 'required|string|max:50|unique:projects,project_code,' . $project->id,
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'project_manager_id' => 'nullable|exists:users,id',
            'project_engineer' => 'nullable|exists:users,id',
            'liaisoning_officer' => 'nullable|exists:users,id',
            'client_id' => 'nullable|exists:leads,id',
            'location' => 'nullable|string',
            'milestones' => 'nullable|array',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy(Request $request, Project $project)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ], [
            'reason.required' => 'Please provide a reason for deletion.',
            'reason.min' => 'Reason must be at least 10 characters long.',
        ]);

        $modelName = 'Project: ' . $project->name . ' (' . $project->project_code . ')';
        return $this->handleDeletion($project, $modelName, $validated['reason'], 'projects.index');
    }

    public function dashboard(Project $project)
    {
        $project->load(['client', 'projectManager', 'tasks.assignedTo']);
        
        $taskStats = [
            'total' => $project->tasks()->count(),
            'pending' => $project->tasks()->where('status', 'pending')->count(),
            'in_progress' => $project->tasks()->where('status', 'in_progress')->count(),
            'completed' => $project->tasks()->where('status', 'completed')->count(),
        ];

        $recentTasks = $project->tasks()->with('assignedTo')->latest()->limit(5)->get();
        
        return view('projects.dashboard', compact('project', 'taskStats', 'recentTasks'));
    }

    public function gantt(Project $project)
    {
        $project->load(['tasks.assignedTo', 'client', 'projectManager']);
        $tasks = $project->tasks()
            ->with('assignedTo')
            ->orderBy('start_date', 'asc')
            ->get();
        
        // Calculate project statistics
        $stats = [
            'total_tasks' => $tasks->count(),
            'completed_tasks' => $tasks->where('status', 'completed')->count(),
            'in_progress_tasks' => $tasks->where('status', 'in_progress')->count(),
            'pending_tasks' => $tasks->where('status', 'pending')->count(),
            'overdue_tasks' => $tasks->filter(function($task) {
                return $task->due_date && $task->due_date->isPast() && !in_array($task->status, ['completed', 'cancelled']);
            })->count(),
        ];
        
        return view('projects.gantt', compact('project', 'tasks', 'stats'));
    }

    public function updateTaskDates(Request $request, Project $project)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $task = $project->tasks()->findOrFail($request->task_id);
        
        $task->update([
            'start_date' => $request->start_date,
            'due_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task dates updated successfully',
            'task' => $task->fresh()
        ]);
    }

    public function updateTaskProgress(Request $request, Project $project)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'progress' => 'required|numeric|min:0|max:100',
        ]);

        $task = $project->tasks()->findOrFail($request->task_id);
        
        // Update status based on progress
        $status = 'pending';
        if ($request->progress == 100) {
            $status = 'completed';
        } elseif ($request->progress > 0) {
            $status = 'in_progress';
        }
        
        $task->update([
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task progress updated successfully',
            'task' => $task->fresh()
        ]);
    }
}
