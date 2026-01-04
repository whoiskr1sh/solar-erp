<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyPolicy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CompanyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CompanyPolicy::with(['creator', 'approver']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('policy_code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('is_mandatory')) {
            $query->where('is_mandatory', $request->is_mandatory);
        }

        $policies = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate summary stats
        $stats = [
            'total_policies' => CompanyPolicy::count(),
            'active_policies' => CompanyPolicy::where('status', 'active')->count(),
            'draft_policies' => CompanyPolicy::where('status', 'draft')->count(),
            'mandatory_policies' => CompanyPolicy::where('is_mandatory', true)->count(),
            'expiring_soon' => CompanyPolicy::expiringSoon()->count(),
            'needs_review' => CompanyPolicy::needsReview()->count(),
            'this_month_created' => CompanyPolicy::whereMonth('created_at', now()->month)->count(),
        ];

        return view('company-policies.index', compact('policies', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('is_active', true)->get();
        return view('company-policies.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'required|in:hr_policies,safety_policies,it_policies,financial_policies,operational_policies,quality_policies,environmental_policies,other',
            'priority' => 'required|in:low,medium,high,critical',
            'effective_date' => 'required|date|after_or_equal:today',
            'review_date' => 'nullable|date|after:effective_date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'version' => 'nullable|string|max:10',
            'is_mandatory' => 'boolean',
            'requires_acknowledgment' => 'boolean',
            'acknowledgment_instructions' => 'nullable|string',
            'approval_workflow' => 'nullable|array',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'draft';

        $policy = CompanyPolicy::create($validated);

        return redirect()->route('company-policies.show', $policy)
            ->with('success', 'Company policy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyPolicy $companyPolicy)
    {
        $companyPolicy->load(['creator', 'approver']);
        return view('company-policies.show', compact('companyPolicy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyPolicy $companyPolicy)
    {
        $users = User::where('is_active', true)->get();
        return view('company-policies.edit', compact('companyPolicy', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyPolicy $companyPolicy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'required|in:hr_policies,safety_policies,it_policies,financial_policies,operational_policies,quality_policies,environmental_policies,other',
            'priority' => 'required|in:low,medium,high,critical',
            'effective_date' => 'required|date',
            'review_date' => 'nullable|date|after:effective_date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'version' => 'nullable|string|max:10',
            'is_mandatory' => 'boolean',
            'requires_acknowledgment' => 'boolean',
            'acknowledgment_instructions' => 'nullable|string',
            'approval_workflow' => 'nullable|array',
        ]);

        $companyPolicy->update($validated);

        return redirect()->route('company-policies.show', $companyPolicy)
            ->with('success', 'Company policy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyPolicy $companyPolicy)
    {
        $companyPolicy->delete();

        return redirect()->route('company-policies.index')
            ->with('success', 'Company policy deleted successfully.');
    }

    /**
     * Approve a policy
     */
    public function approve(Request $request, CompanyPolicy $companyPolicy)
    {
        $validated = $request->validate([
            'approval_notes' => 'nullable|string',
        ]);

        $companyPolicy->update([
            'status' => 'active',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'],
        ]);

        return redirect()->route('company-policies.show', $companyPolicy)
            ->with('success', 'Policy approved successfully.');
    }

    /**
     * Archive a policy
     */
    public function archive(CompanyPolicy $companyPolicy)
    {
        $companyPolicy->update(['status' => 'archived']);

        return redirect()->route('company-policies.show', $companyPolicy)
            ->with('success', 'Policy archived successfully.');
    }

    /**
     * Activate a policy
     */
    public function activate(CompanyPolicy $companyPolicy)
    {
        $companyPolicy->update(['status' => 'active']);

        return redirect()->route('company-policies.show', $companyPolicy)
            ->with('success', 'Policy activated successfully.');
    }

    /**
     * Dashboard view
     */
    public function dashboard()
    {
        $stats = [
            'total_policies' => CompanyPolicy::count(),
            'active_policies' => CompanyPolicy::where('status', 'active')->count(),
            'draft_policies' => CompanyPolicy::where('status', 'draft')->count(),
            'mandatory_policies' => CompanyPolicy::where('is_mandatory', true)->count(),
            'expiring_soon' => CompanyPolicy::expiringSoon()->count(),
            'needs_review' => CompanyPolicy::needsReview()->count(),
            'this_month_created' => CompanyPolicy::whereMonth('created_at', now()->month)->count(),
        ];

        $recentPolicies = CompanyPolicy::with(['creator'])->latest()->limit(5)->get();
        $expiringPolicies = CompanyPolicy::expiringSoon()->limit(5)->get();
        $needsReviewPolicies = CompanyPolicy::needsReview()->limit(5)->get();

        // Category breakdown
        $categoryBreakdown = CompanyPolicy::groupBy('category')
            ->selectRaw('category, count(*) as count')
            ->pluck('count', 'category');

        // Status breakdown
        $statusBreakdown = CompanyPolicy::groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->pluck('count', 'status');

        return view('company-policies.dashboard', compact(
            'stats',
            'recentPolicies',
            'expiringPolicies',
            'needsReviewPolicies',
            'categoryBreakdown',
            'statusBreakdown'
        ));
    }
}
