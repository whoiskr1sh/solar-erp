<?php

namespace App\Http\Controllers;

use App\Models\ProjectProfitability;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectProfitabilityController extends Controller
{
                            public function index(Request $request)
    {
        $query = ProjectProfitability::with(['project', 'creator', 'reviewer']);

        // Apply filters
        if ($request->filled('search')) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        $profitabilities = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate summary stats
        $stats = [
            'total_revenue' => ProjectProfitability::sum('total_revenue'),
            'total_costs' => ProjectProfitability::sum('total_costs'), 
            'total_profit' => ProjectProfitability::sum('gross_profit'),
            'avg_margin' => ProjectProfitability::avg('gross_margin_percentage'),
            'total_projects' => ProjectProfitability::distinct('project_id')->count(),
            'approved_projects' => ProjectProfitability::where('status', 'approved')->distinct('project_id')->count(),
        ];

        return view('project-profitabilities.index', compact('profitabilities', 'stats'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        return view('project-profitabilities.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'period' => 'required|string|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            
            // Revenue fields
            'contract_value' => 'required|numeric|min:0',
            'progress_billing' => 'required|numeric|min:0',
            'overrun_revenue' => 'required|numeric|min:0',
            
            // Cost fields
            'material_costs' => 'required|numeric|min:0',
            'labor_costs' => 'required|numeric|min:0',
            'equipment_costs' => 'required|numeric|min:0',
            'transportation_costs' => 'required|numeric|min:0',
            'permits_costs' => 'required|numeric|min:0',
            'overhead_costs' => 'required|numeric|min:0',
            'subcontractor_costs' => 'required|numeric|min:0',
            
            // Additional fields
            'change_order_amount' => 'required|numeric|min:0',
            'retention_amount' => 'required|numeric|min:0',
            'days_completed' => 'required|integer|min:0',
            'total_days' => 'required|integer|min:1',
            
            'status' => 'required|string|in:draft,reviewed,approved,final',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        
        ProjectProfitability::create($validated);

        return redirect()->route('project-profitabilities.index')
            ->with('success', 'Project profitability report created successfully.');
    }

    public function show(ProjectProfitability $projectProfitability)
    {
        $projectProfitability->load(['project', 'creator', 'reviewer']);
        return view('project-profitabilities.show', compact('projectProfitability'));
    }

    public function edit(ProjectProfitability $projectProfitability)
    {
        $projects = Project::where('status', 'active')->get();
        return view('project-profitabilities.edit', compact('projectProfitability', 'projects'));
    }

    public function update(Request $request, ProjectProfitability $projectProfitability)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'period' => 'required|string|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            
            // Revenue fields
            'contract_value' => 'required|numeric|min:0',
            'progress_billing' => 'required|numeric|min:0',
            'overrun_revenue' => 'required|numeric|min:0',
            
            // Cost fields
            'material_costs' => 'required|numeric|min:0',
            'labor_costs' => 'required|numeric|min:0',
            'equipment_costs' => 'required|numeric|min:0',
            'transportation_costs' => 'required|numeric|min:0',
            'permits_costs' => 'required|numeric|min:0',
            'overhead_costs' => 'required|numeric|min:0',
            'subcontractor_costs' => 'required|numeric|min:0',
            
            // Additional fields
            'change_order_amount' => 'required|numeric|min:0',
            'retention_amount' => 'required|numeric|min:0',
            'days_completed' => 'required|integer|min:0',
            'total_days' => 'required|integer|min:1',
            
            'status' => 'required|string|in:draft,reviewed,approved,final',
            'notes' => 'nullable|string',
        ]);

        $projectProfitability->update($validated);

        return redirect()->route('project-profitabilities.index')
            ->with('success', 'Project profitability report updated successfully.');
    }

    public function destroy(ProjectProfitability $projectProfitability)
    {
        $projectProfitability->delete();

        return redirect()->route('project-profitabilities.index')
            ->with('success', 'Project profitability report deleted successfully.');
    }

    public function approve(ProjectProfitability $projectProfitability)
    {
        $projectProfitability->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Project profitability report approved successfully.');
    }

    public function dashboard()
    {
        // Overall profitability summary
        $summary = [
            'total_revenue' => ProjectProfitability::sum('total_revenue'),
            'total_costs' => ProjectProfitability::sum('total_costs'),
            'total_profit' => ProjectProfitability::sum('gross_profit'),
            'avg_gross_margin' => ProjectProfitability::avg('gross_margin_percentage'),
            'total_projects' => ProjectProfitability::distinct('project_id')->count(),
        ];

        // Most profitable projects
        $topProjects = ProjectProfitability::with('project')
            ->orderByDesc('gross_profit')
            ->take(5)
            ->get();

        // Project count by status
        $statusBreakdown = ProjectProfitability::groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->pluck('count', 'status');

        // Recent reports
        $recentReports = ProjectProfitability::with(['project', 'creator'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('project-profitabilities.dashboard', compact('summary', 'topProjects', 'statusBreakdown', 'recentReports'));
    }
}
