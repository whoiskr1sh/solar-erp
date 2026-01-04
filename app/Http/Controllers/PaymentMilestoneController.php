<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMilestone;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentMilestoneController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentMilestone::active()->with(['project', 'quotation', 'creator', 'assignee']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('milestone_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('milestone_type')) {
            $query->where('milestone_type', $request->milestone_type);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('due_this_month')) {
            if ($request->due_this_month) {
                $query->whereMonth('due_date', now()->month)
                     ->whereYear('due_date', now()->year);
            }
        }

        if ($request->filled('overdue')) {
            if ($request->overdue) {
                $query->overdue();
            }
        }

        $milestones = $query->orderBy('due_date', 'asc')->paginate(15);

        // Calculate summary stats
        $stats = [
            'total_milestones' => PaymentMilestone::count(),
            'pending_milestones' => PaymentMilestone::where('status', 'pending')->count(),
            'in_progress_milestones' => PaymentMilestone::where('status', 'in_progress')->count(),
            'completed_milestones' => PaymentMilestone::where('status', 'completed')->count(),
            'paid_milestones' => PaymentMilestone::where('status', 'paid')->count(),
            'overdue_milestones' => PaymentMilestone::overdue()->count(),
            'total_milestone_amount' => PaymentMilestone::sum('milestone_amount'),
            'total_paid_amount' => PaymentMilestone::sum('paid_amount'),
            'pending_amount' => PaymentMilestone::sum(\DB::raw('milestone_amount - paid_amount')),
            'this_month_due' => PaymentMilestone::whereMonth('due_date', now()->month)
                                              ->whereYear('due_date', now()->year)
                                              ->sum('milestone_amount'),
        ];

        // Get filter options
        $projects = Project::where('status', 'active')->get();
        
        return view('payment-milestones.index', compact('milestones', 'stats', 'projects'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        $quotations = Quotation::where('status', 'active')->get();
        $users = User::where('is_active', true)->get();
        
        return view('payment-milestones.create', compact('projects', 'quotations', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'quotation_id' => 'nullable|exists:quotations,id',
            'milestone_amount' => 'required|numeric|min:0',
            'pay_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'milestone_type' => 'required|in:advance,progress,completion,retention,final',
            'milestone_percentage' => 'nullable|integer|min:0|max:100',
            'planned_date' => 'required|date',
            'due_date' => 'required|date|after:planned_date',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,paid,overdue,cancelled',
            'payment_status' => 'required|in:pending,paid,partial,overdue,cancelled',
            'payment_method' => 'nullable|in:cash,cheque,bank_transfer,online,upi,card',
            'payment_reference' => 'nullable|string|max:255',
            'payment_notes' => 'nullable|string',
            'milestone_notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['created_by'] = Auth::id();
        
        PaymentMilestone::create($validated);

        return redirect()->route('payment-milestones.index')
            ->with('success', 'Payment milestone created successfully.');
    }

    public function show(PaymentMilestone $paymentMilestone)
    {
        $paymentMilestone->load(['project', 'quotation', 'creator', 'assignee', 'payer']);
        
        return view('payment-milestones.show', compact('paymentMilestone'));
    }

    public function edit(PaymentMilestone $paymentMilestone)
    {
        $projects = Project::where('status', 'active')->get();
        $quotations = Quotation::where('status', 'active')->get();
        $users = User::where('is_active', true)->get();
        
        return view('payment-milestones.edit', compact('paymentMilestone', 'projects', 'quotations', 'users'));
    }

    public function update(Request $request, PaymentMilestone $paymentMilestone)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'quotation_id' => 'nullable|exists:quotations,id',
            'milestone_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'milestone_type' => 'required|in:advance,progress,completion,retention,final',
            'milestone_percentage' => 'nullable|integer|min:0|max:100',
            'planned_date' => 'required|date',
            'due_date' => 'required|date|after:planned_date',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,paid,overdue,cancelled',
            'payment_status' => 'required|in:pending,paid,partial,overdue,cancelled',
            'payment_method' => 'nullable|in:cash,cheque,bank_transfer,online,upi,card',
            'payment_reference' => 'nullable|string|max:255',
            'payment_notes' => 'nullable|string',
            'milestone_notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $paymentMilestone->update($validated);

        return redirect()->route('payment-milestones.show', $paymentMilestone)
            ->with('success', 'Payment milestone updated successfully.');
    }

    public function destroy(PaymentMilestone $paymentMilestone)
    {
        $paymentMilestone->update(['is_active' => false]);

        return redirect()->route('payment-milestones.index')
            ->with('success', 'Payment milestone deleted successfully.');
    }

    public function markPaid(Request $request, PaymentMilestone $paymentMilestone)
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,cheque,bank_transfer,online,upi,card',
            'payment_reference' => 'nullable|string|max:255',
            'payment_notes' => 'nullable|string',
        ]);

        $paymentMilestone->markAsPaid(
            $validated['paid_amount'],
            $validated['payment_method'],
            $validated['payment_reference'] ?? null,
            $validated['payment_notes'] ?? null
        );

        return redirect()->route('payment-milestones.show', $paymentMilestone)
            ->with('success', 'Payment recorded successfully.');
    }

    public function markCompleted(PaymentMilestone $paymentMilestone)
    {
        $paymentMilestone->update([
            'status' => 'completed',
            'milestone_percentage' => 100,
        ]);

        return redirect()->route('payment-milestones.show', $paymentMilestone)
            ->with('success', 'Milestone marked as completed.');
    }

    public function markInProgress(PaymentMilestone $paymentMilestone)
    {
        $paymentMilestone->update([
            'status' => 'in_progress',
            'milestone_percentage' => min(90, $paymentMilestone->milestone_percentage ?: 50),
        ]);

        return redirect()->route('payment-milestones.show', $paymentMilestone)
            ->with('success', 'Milestone marked as in progress.');
    }

    public function dashboard()
    {
        // Milestone summary stats
        $summary = [
            'total_milestones' => PaymentMilestone::count(),
            'pending_milestones' => PaymentMilestone::where('status', 'pending')->count(),
            'completed' => PaymentMilestone::where('status', 'completed')->count(),
            'paid' => PaymentMilestone::where('status', 'paid')->count(),
            'overdue' => PaymentMilestone::overdue()->count(),
            'total_milestone_amount' => PaymentMilestone::sum('milestone_amount'),
            'total_paid_amount' => PaymentMilestone::sum('paid_amount'),
            'pending_amount' => PaymentMilestone::sum(\DB::raw('milestone_amount - paid_amount')),
        ];

        // Calculate variance
        $summary['variance'] = $summary['total_milestone_amount'] - $summary['total_paid_amount'];
        $summary['variance_amount'] = $summary['total_milestone_amount'] - $summary['total_paid_amount'];
        $summary['variance_percentage'] = $summary['total_milestone_amount'] > 0 
            ? round(($summary['variance_amount'] / $summary['total_milestone_amount']) * 100, 2) 
            : 0;

        // Top milestones by amount
        $topMilestones = PaymentMilestone::with(['project', 'quotation'])
            ->orderByDesc('milestone_amount')
            ->take(5)
            ->get();

        // Status breakdown
        $statusBreakdown = PaymentMilestone::groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->pluck('count', 'status');

        // Type breakdown
        $typeBreakdown = PaymentMilestone::groupBy('milestone_type')
            ->selectRaw('milestone_type, count(*) as count')
            ->pluck('count', 'milestone_type');

        // Recent milestones
        $recentMilestones = PaymentMilestone::with(['project', 'quotation', 'creator'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Overdue milestones
        $overdueMilestones = PaymentMilestone::overdue()
            ->with(['project', 'quotation'])
            ->take(5)
            ->get();

        return view('payment-milestones.dashboard', compact(
            'summary', 
            'topMilestones', 
            'statusBreakdown', 
            'typeBreakdown', 
            'recentMilestones',
            'overdueMilestones'
        ));
    }
}