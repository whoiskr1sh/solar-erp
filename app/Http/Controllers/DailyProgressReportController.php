<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyProgressReport;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DailyProgressReportController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyProgressReport::with(['project', 'submittedBy', 'approvedBy']);

        // Filters
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('report_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('report_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('work_performed', 'like', '%' . $searchTerm . '%')
                  ->orWhere('challenges_faced', 'like', '%' . $searchTerm . '%')
                  ->orWhere('next_day_plan', 'like', '%' . $searchTerm . '%')
                  ->orWhere('materials_used', 'like', '%' . $searchTerm . '%')
                  ->orWhere('equipment_used', 'like', '%' . $searchTerm . '%')
                  ->orWhere('remarks', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('project', function($projectQuery) use ($searchTerm) {
                      $projectQuery->where('name', 'like', '%' . $searchTerm . '%')
                                   ->orWhere('project_code', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('submittedBy', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $reports = $query->latest('report_date')->paginate(15);
        $projects = Project::where('status', '!=', 'cancelled')->get();
        
        $stats = [
            'total' => DailyProgressReport::count(),
            'pending' => DailyProgressReport::where('status', 'pending')->count(),
            'approved' => DailyProgressReport::where('status', 'approved')->count(),
            'rejected' => DailyProgressReport::where('status', 'rejected')->count(),
            'this_month' => DailyProgressReport::whereMonth('report_date', now()->month)->count(),
            'this_week' => DailyProgressReport::whereBetween('report_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('dpr.index', compact('reports', 'projects', 'stats'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        return view('dpr.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'report_date' => 'required|date',
            'weather_condition' => 'required|in:sunny,cloudy,rainy,stormy,foggy',
            'work_performed' => 'required|string',
            'work_hours' => 'required|numeric|min:0|max:24',
            'workers_present' => 'required|integer|min:0',
            'materials_used' => 'nullable|string',
            'equipment_used' => 'nullable|string',
            'challenges_faced' => 'nullable|string',
            'next_day_plan' => 'nullable|string',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remarks' => 'nullable|string',
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('dpr-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $report = DailyProgressReport::create([
            'project_id' => $request->project_id,
            'report_date' => $request->report_date,
            'weather_condition' => $request->weather_condition,
            'work_performed' => $request->work_performed,
            'work_hours' => $request->work_hours,
            'workers_present' => $request->workers_present,
            'materials_used' => $request->materials_used,
            'equipment_used' => $request->equipment_used,
            'challenges_faced' => $request->challenges_faced,
            'next_day_plan' => $request->next_day_plan,
            'photos' => $photoPaths,
            'status' => 'pending',
            'submitted_by' => Auth::id(),
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('dpr.show', $report)->with('success', 'Daily Progress Report submitted successfully!');
    }

    public function show(DailyProgressReport $dpr)
    {
        $dpr->load(['project', 'submittedBy', 'approvedBy']);
        return view('dpr.show', compact('dpr'));
    }

    public function edit(DailyProgressReport $dpr)
    {
        // Only allow editing if pending and user is the submitter
        if ($dpr->status !== 'pending' || $dpr->submitted_by !== Auth::id()) {
            return redirect()->route('dpr.show', $dpr)->with('error', 'You can only edit pending reports that you submitted.');
        }

        $projects = Project::where('status', 'active')->get();
        return view('dpr.edit', compact('dpr', 'projects'));
    }

    public function update(Request $request, DailyProgressReport $dpr)
    {
        // Only allow updating if pending and user is the submitter
        if ($dpr->status !== 'pending' || $dpr->submitted_by !== Auth::id()) {
            return redirect()->route('dpr.show', $dpr)->with('error', 'You can only edit pending reports that you submitted.');
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'report_date' => 'required|date',
            'weather_condition' => 'required|in:sunny,cloudy,rainy,stormy,foggy',
            'work_performed' => 'required|string',
            'work_hours' => 'required|numeric|min:0|max:24',
            'workers_present' => 'required|integer|min:0',
            'materials_used' => 'nullable|string',
            'equipment_used' => 'nullable|string',
            'challenges_faced' => 'nullable|string',
            'next_day_plan' => 'nullable|string',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remarks' => 'nullable|string',
        ]);

        $photoPaths = $dpr->photos ?? [];
        if ($request->hasFile('photos')) {
            // Delete old photos
            foreach ($photoPaths as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
            
            // Upload new photos
            $photoPaths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('dpr-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $dpr->update([
            'project_id' => $request->project_id,
            'report_date' => $request->report_date,
            'weather_condition' => $request->weather_condition,
            'work_performed' => $request->work_performed,
            'work_hours' => $request->work_hours,
            'workers_present' => $request->workers_present,
            'materials_used' => $request->materials_used,
            'equipment_used' => $request->equipment_used,
            'challenges_faced' => $request->challenges_faced,
            'next_day_plan' => $request->next_day_plan,
            'photos' => $photoPaths,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('dpr.show', $dpr)->with('success', 'Daily Progress Report updated successfully!');
    }

    public function destroy(DailyProgressReport $dpr)
    {
        // Only allow deletion if pending and user is the submitter
        if ($dpr->status !== 'pending' || $dpr->submitted_by !== Auth::id()) {
            return redirect()->route('dpr.index')->with('error', 'You can only delete pending reports that you submitted.');
        }

        // Delete photos
        if ($dpr->photos) {
            foreach ($dpr->photos as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }

        $dpr->delete();
        return redirect()->route('dpr.index')->with('success', 'Daily Progress Report deleted successfully!');
    }

    public function approve(Request $request, DailyProgressReport $dpr)
    {
        $request->validate([
            'remarks' => 'nullable|string',
        ]);

        $dpr->approve(Auth::id(), $request->remarks);
        
        return redirect()->route('dpr.show', $dpr)->with('success', 'Daily Progress Report approved successfully!');
    }

    public function dashboard()
    {
        $stats = [
            'total_reports' => DailyProgressReport::count(),
            'pending_reports' => DailyProgressReport::where('status', 'pending')->count(),
            'approved_reports' => DailyProgressReport::where('status', 'approved')->count(),
            'this_month_reports' => DailyProgressReport::whereMonth('report_date', now()->month)->count(),
            'this_week_reports' => DailyProgressReport::whereBetween('report_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        $recentReports = DailyProgressReport::with(['project', 'submittedBy'])
            ->latest('report_date')
            ->take(10)
            ->get();

        $projectStats = DailyProgressReport::with('project')
            ->selectRaw('project_id, count(*) as report_count')
            ->groupBy('project_id')
            ->orderBy('report_count', 'desc')
            ->take(5)
            ->get();

        return view('dpr.dashboard', compact('stats', 'recentReports', 'projectStats'));
    }
}
