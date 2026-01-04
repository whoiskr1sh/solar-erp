<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteExpense;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Traits\HandlesDeletionApproval;

class SiteExpenseController extends Controller
{
    use HandlesDeletionApproval;
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = SiteExpense::with(['project', 'creator', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('expense_number', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%')
                  ->orWhere('vendor_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by approval level (for HR and Admin to see pending approvals)
        if ($request->filled('approval_level')) {
            $query->where('approval_level', $request->approval_level);
        }

        // If not admin/HR, show only own expenses
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('HR MANAGER')) {
            $query->where('created_by', $user->id);
        }

        $expenses = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::where('status', '!=', 'cancelled')->get();

        // Calculate user-specific stats
        $statsQuery = SiteExpense::query();
        
        // Apply same filter as main query - if not admin/HR, show only own expenses
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('HR MANAGER')) {
            $statsQuery->where('created_by', $user->id);
        }

        $stats = [
            'total' => $statsQuery->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'pending_hr' => (clone $statsQuery)->where('status', 'pending')->where('approval_level', 'hr')->count(),
            'pending_admin' => (clone $statsQuery)->where('status', 'pending')->where('approval_level', 'admin')->count(),
            'approved' => (clone $statsQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $statsQuery)->where('status', 'rejected')->count(),
            'paid' => (clone $statsQuery)->where('status', 'paid')->count(),
            'total_amount' => (clone $statsQuery)->sum('amount'),
        ];

        return view('site-expenses.index', compact('expenses', 'projects', 'stats'));
    }

    public function create()
    {
        $projects = Project::where('status', '!=', 'cancelled')->get();
        return view('site-expenses.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'site_location' => 'nullable|string|max:255',
            'expense_category' => 'required|string|in:material,labor,transport,misc,equipment,other',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string|in:cash,card,transfer,cheque',
            'vendor_name' => 'nullable|string|max:255',
            'receipt_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = auth()->user();
        $validated['created_by'] = $user->id;
        $validated['status'] = 'pending';
        $validated['approval_level'] = 'hr'; // Always start with HR approval

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('site-expense-receipts', 'public');
        }

        $expense = SiteExpense::create($validated);

        // Always send to HR first for approval
        $this->sendApprovalRequestToHR($expense);

        return redirect()->route('site-expenses.index')
            ->with('success', 'Site expense created successfully. Pending HR approval.');
    }

    public function show(SiteExpense $siteExpense)
    {
        $siteExpense->load(['project', 'creator', 'approver', 'hrApprover']);
        return view('site-expenses.show', compact('siteExpense'));
    }

    public function edit(SiteExpense $siteExpense)
    {
        if ($siteExpense->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot edit approved or rejected expenses.');
        }

        $projects = Project::where('status', '!=', 'cancelled')->get();
        return view('site-expenses.edit', compact('siteExpense', 'projects'));
    }

    public function update(Request $request, SiteExpense $siteExpense)
    {
        if ($siteExpense->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot edit approved or rejected expenses.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'site_location' => 'nullable|string|max:255',
            'expense_category' => 'required|string|in:material,labor,transport,misc,equipment,other',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string|in:cash,card,transfer,cheque',
            'vendor_name' => 'nullable|string|max:255',
            'receipt_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('receipt')) {
            if ($siteExpense->receipt_path) {
                \Storage::disk('public')->delete($siteExpense->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('site-expense-receipts', 'public');
        }

        $siteExpense->update($validated);

        return redirect()->route('site-expenses.index')
            ->with('success', 'Site expense updated successfully.');
    }

    public function destroy(Request $request, SiteExpense $siteExpense)
    {
        if ($siteExpense->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot delete approved or rejected expenses.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ], [
            'reason.required' => 'Please provide a reason for deletion.',
            'reason.min' => 'Reason must be at least 10 characters long.',
        ]);

        // Delete receipt file if exists
        if ($siteExpense->receipt_path) {
            \Storage::disk('public')->delete($siteExpense->receipt_path);
        }

        $modelName = 'Site Expense: ' . $siteExpense->expense_number . ' - ' . $siteExpense->title;
        return $this->handleDeletion($siteExpense, $modelName, $validated['reason'], 'site-expenses.index');
    }

    public function approve(SiteExpense $siteExpense)
    {
        $user = auth()->user();
        
        if ($siteExpense->status !== 'pending') {
            return redirect()->back()->with('error', 'This expense has already been processed.');
        }

        // Multi-level approval flow: HR -> Admin
        if ($siteExpense->approval_level === 'hr') {
            // HR approval level - Only HR Manager can approve
            if (!$user->hasRole('HR MANAGER')) {
                return redirect()->back()->with('error', 'Only HR Manager can approve at this level.');
            }
            
            $siteExpense->update([
                'approval_level' => 'admin',
                'hr_approved_by' => $user->id,
                'hr_approved_at' => now(),
            ]);
            
            // Forward to Admin
            $this->sendApprovalRequestToAdmin($siteExpense);
            
            return redirect()->back()->with('success', 'HR approval granted. Forwarded to Admin for final approval.');
            
        } elseif ($siteExpense->approval_level === 'admin') {
            // Admin final approval
            if (!$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only Admin can provide final approval.');
            }
            
            $siteExpense->update([
                'status' => 'approved',
                'approval_level' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
            
            return redirect()->back()->with('success', 'Site expense fully approved and finalized.');
        }

        return redirect()->back()->with('error', 'Invalid approval level.');
    }

    public function reject(Request $request, SiteExpense $siteExpense)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($siteExpense->status !== 'pending') {
            return redirect()->back()->with('error', 'This expense has already been processed.');
        }

        // Multi-level rejection
        if ($siteExpense->approval_level === 'hr') {
            if (!$user->hasRole('HR MANAGER')) {
                return redirect()->back()->with('error', 'Only HR Manager can reject at this level.');
            }
            
            $siteExpense->update([
                'status' => 'rejected',
                'approval_level' => 'rejected',
                'hr_rejection_reason' => $validated['rejection_reason'],
            ]);
            
        } elseif ($siteExpense->approval_level === 'admin') {
            if (!$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only Admin can reject at this level.');
            }
            
            $siteExpense->update([
                'status' => 'rejected',
                'approval_level' => 'rejected',
                'admin_rejection_reason' => $validated['rejection_reason'],
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
        } else {
            return redirect()->back()->with('error', 'Invalid approval level for rejection.');
        }

        return redirect()->back()->with('success', 'Site expense rejected.');
    }

    /**
     * Check if user is a subordinate (not HR or Admin)
     */
    private function isSubordinate($user)
    {
        return !$user->hasRole('SUPER ADMIN') && !$user->hasRole('HR MANAGER');
    }

    /**
     * Send approval request to HR
     */
    private function sendApprovalRequestToHR(SiteExpense $expense)
    {
        try {
            $hrManagers = \App\Models\User::whereHas('roles', function($q) {
                $q->where('name', 'HR MANAGER');
            })->get();

            foreach ($hrManagers as $hr) {
                // Send email notification
                // Mail::to($hr->email)->send(new SiteExpenseApprovalNotification($expense));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send HR approval notification', [
                'expense_id' => $expense->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send approval request to Admin
     */
    private function sendApprovalRequestToAdmin(SiteExpense $expense)
    {
        try {
            $admins = \App\Models\User::whereHas('roles', function($q) {
                $q->where('name', 'SUPER ADMIN');
            })->get();

            foreach ($admins as $admin) {
                // Send email notification
                // Mail::to($admin->email)->send(new SiteExpenseApprovalNotification($expense));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send Admin approval notification', [
                'expense_id' => $expense->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
