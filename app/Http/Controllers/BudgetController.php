<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\BudgetCategory;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::with(['category', 'project', 'creator', 'approver']);

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('budget_number', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('budget_category_id', $request->category);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        if ($request->filled('period')) {
            $query->where('budget_period', $request->period);
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }

        $budgets = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate summary stats
        $stats = [
            'total_budgets' => Budget::count(),
            'draft_budgets' => Budget::where('status', 'draft')->count(),
            'pending_budgets' => Budget::where('status', 'pending')->count(),
            'approved_budgets' => Budget::where('status', 'approved')->count(),
            'rejected_budgets' => Budget::where('status', 'rejected')->count(),
            'completed_budgets' => Budget::where('status', 'completed')->count(),
            'total_budget_amount' => Budget::where('status', '!=', 'rejected')->sum('budget_amount'),
            'total_actual_amount' => Budget::sum('actual_amount'),
            'over_budget_count' => Budget::whereRaw('actual_amount > budget_amount')->count(),
            'this_month_budget' => Budget::whereDate('start_date', '>=', now()->startOfMonth())
                                     ->whereDate('end_date', '<=', now()->endOfMonth())->sum('budget_amount'),
        ];

        // Get filter options
        $categories = BudgetCategory::active()->get();
        $projects = Project::where('status', 'active')->get();

        return view('budgets.index', compact('budgets', 'stats', 'categories', 'projects'));
    }

    public function create()
    {
        $categories = BudgetCategory::active()->get();
        $projects = Project::where('status', 'active')->get();
        
        return view('budgets.create', compact('categories', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget_category_id' => 'required|exists:budget_categories,id',
            'project_id' => 'nullable|exists:projects,id',
            'budget_amount' => 'required|numeric|min:0',
            'actual_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'budget_period' => 'required|string|in:monthly,quarterly,yearly,project',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string|in:draft,pending,approved,rejected,completed',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();
        
        Budget::create($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Budget created successfully.');
    }

    public function show(Budget $budget)
    {
        $budget->load(['category', 'project', 'creator', 'approver']);
        return view('budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $categories = BudgetCategory::active()->get();
        $projects = Project::where('status', 'active')->get();
        
        return view('budgets.edit', compact('budget', 'categories', 'projects'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget_category_id' => 'required|exists:budget_categories,id',
            'project_id' => 'nullable|exists:projects,id',
            'budget_amount' => 'required|numeric|min:0',
            'actual_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'budget_period' => 'required|string|in:monthly,quarterly,yearly,project',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string|in:draft,pending,approved,rejected,completed',
            'notes' => 'nullable|string',
        ]);

        $budget->update($validated);

        return redirect()->route('budgets.index')
            ->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', 'Budget deleted successfully.');
    }

    public function approve(Budget $budget)
    {
        $budget->update([
            'status' => 'approved',
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Budget approved successfully.');
    }

    public function reject(Budget $budget)
    {
        $budget->update([
            'status' => 'rejected',
            'is_approved' => false,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Budget rejected successfully.');
    }

    public function setActualAmount(Request $request, Budget $budget)
    {
        $request->validate([
            'actual_amount' => 'required|numeric|min:0',
        ]);

        $budget->update([
            'actual_amount' => $request->actual_amount,
            'status' => 'completed',
        ]);

        return redirect()->back()
            ->with('success', 'Actual amount updated successfully.');
    }

    public function dashboard()
    {
        // Budget summary stats
        $summary = [
            'total_budget_amount' => Budget::where('status', '!=', 'rejected')->sum('budget_amount'),
            'total_actual_amount' => Budget::sum('actual_amount'),
            'total_budgets' => Budget::count(),
            'approved_budgets' => Budget::where('status', 'approved')->count(),
            'pending_budgets' => Budget::where('status', 'pending')->count(),
            'over_budget_count' => Budget::whereRaw('actual_amount > budget_amount')->count(),
        ];

        // Calculate variance
        $summary['variance'] = $summary['total_actual_amount'] - $summary['total_budget_amount'];
        $summary['variance_amount'] = $summary['total_actual_amount'] - $summary['total_budget_amount'];
        $summary['variance_percentage'] = $summary['total_budget_amount'] > 0 
            ? round(($summary['variance_amount'] / $summary['total_budget_amount']) * 100, 2) 
            : 0;

        // Add active budgets count
        $summary['active_budgets'] = Budget::where('status', 'approved')->count();

        // Top budgets by amount
        $topBudgets = Budget::with(['category', 'project'])
            ->orderByDesc('budget_amount')
            ->take(5)
            ->get();

        // Status breakdown
        $statusBreakdown = Budget::groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->pluck('count', 'status');

        // Category breakdown with count
        $categoryBreakdown = Budget::join('budget_categories', 'budgets.budget_category_id', '=', 'budget_categories.id')
            ->groupBy('budget_categories.id', 'budget_categories.name', 'budget_categories.color')
            ->selectRaw('budget_categories.name, budget_categories.color, count(*) as budget_count, sum(budgets.budget_amount) as total_amount')
            ->get();

        // Recent budgets
        $recentBudgets = Budget::with(['category', 'project', 'creator'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('budgets.dashboard', compact('summary', 'topBudgets', 'statusBreakdown', 'categoryBreakdown', 'recentBudgets'));
    }
}
