<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Project;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'project', 'creator', 'approver', 'managerApprover', 'hrApprover']);

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('expense_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('expense_category_id', $request->category);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15);

        // Calculate stats
        $stats = [
            'total' => Expense::count(),
            'pending' => Expense::where('status', 'pending')->count(),
            'approved' => Expense::where('status', 'approved')->count(),
            'paid' => Expense::where('status', 'paid')->count(),
            'rejected' => Expense::where('status', 'rejected')->count(),
            'total_amount' => Expense::where('status', 'approved')->sum('amount'),
            'month_total' => Expense::whereMonth('expense_date', now()->month)->sum('amount'),
        ];

        $categories = ExpenseCategory::where('is_active', true)->get();
        
        return view('expenses.index', compact('expenses', 'stats', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ExpenseCategory::where('is_active', true)->get();
        $projects = Project::where('status', 'active')->get();
        
        return view('expenses.create', compact('categories', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'project_id' => 'nullable|exists:projects,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|in:USD,EUR,GBP,INR',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string|in:cash,card,transfer,cheque',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['approval_level'] = 'manager'; // Start with Manager approval

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense = Expense::create($validated);

        // Send notification to Managers for first-level approval
        $this->sendApprovalRequestToManagers($expense);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense created successfully. Pending Manager approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load(['category', 'project', 'creator', 'approver', 'managerApprover', 'hrApprover']);
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::where('is_active', true)->get();
        $projects = Project::where('status', 'active')->get();
        
        return view('expenses.edit', compact('expense', 'categories', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'project_id' => 'nullable|exists:projects,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|in:USD,EUR,GBP,INR',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string|in:cash,card,transfer,cheque',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('receipt')) {
            if ($expense->receipt_path) {
                \Storage::disk('public')->delete($expense->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        if ($expense->receipt_path) {
            \Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Approve the expense - Multi-level approval flow
     */
    public function approve(Request $request, Expense $expense)
    {
        $user = Auth::user();
        
        if ($expense->status !== 'pending') {
            return redirect()->back()->with('error', 'This expense has already been processed.');
        }

        // Multi-level approval flow: Manager → HR → Admin
        if ($expense->approval_level === 'manager') {
            // Manager approval
            if (!$user->hasRole('PROJECT MANAGER') && !$user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only Managers can approve at this level.');
            }
            
            $expense->update([
                'approval_level' => 'hr',
                'manager_approved_by' => $user->id,
                'manager_approved_at' => now(),
            ]);
            
            // Send notification to HR
            $this->sendApprovalRequestToHR($expense);
            
            return redirect()->back()->with('success', 'Manager approval granted. Forwarded to HR for approval.');
            
        } elseif ($expense->approval_level === 'hr') {
            // HR approval
            if (!$user->hasRole('HR MANAGER') && !$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only HR Manager can approve at this level.');
            }
            
            $expense->update([
                'approval_level' => 'admin',
                'hr_approved_by' => $user->id,
                'hr_approved_at' => now(),
            ]);
            
            // Send notification to Admin
            $this->sendApprovalRequestToAdmin($expense);
            
            return redirect()->back()->with('success', 'HR approval granted. Forwarded to Admin for final approval.');
            
        } elseif ($expense->approval_level === 'admin') {
            // Admin final approval
            if (!$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only Admin can provide final approval.');
            }
            
            $expense->update([
                'status' => 'approved',
                'approval_level' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
            
            // Notify creator
            $this->notifyCreatorOfApproval($expense);
            
            return redirect()->back()->with('success', 'Expense fully approved and finalized.');
        }

        return redirect()->back()->with('error', 'Invalid approval level.');
    }

    /**
     * Reject the expense - Multi-level rejection
     */
    public function reject(Request $request, Expense $expense)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($expense->status !== 'pending') {
            return redirect()->back()->with('error', 'This expense has already been processed.');
        }

        // Multi-level rejection based on current approval level
        if ($expense->approval_level === 'manager') {
            if (!$user->hasRole('PROJECT MANAGER') && !$user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only Managers can reject at this level.');
            }
            
            $expense->update([
                'status' => 'rejected',
                'approval_level' => 'rejected',
                'manager_rejection_reason' => $validated['rejection_reason'],
            ]);
            
        } elseif ($expense->approval_level === 'hr') {
            if (!$user->hasRole('HR MANAGER') && !$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only HR Manager can reject at this level.');
            }
            
            $expense->update([
                'status' => 'rejected',
                'approval_level' => 'rejected',
                'hr_rejection_reason' => $validated['rejection_reason'],
            ]);
            
        } elseif ($expense->approval_level === 'admin') {
            if (!$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only Admin can reject at this level.');
            }
            
            $expense->update([
                'status' => 'rejected',
                'approval_level' => 'rejected',
                'admin_rejection_reason' => $validated['rejection_reason'],
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
        } else {
            return redirect()->back()->with('error', 'Invalid approval level for rejection.');
        }

        // Notify creator
        $this->notifyCreatorOfRejection($expense, $validated['rejection_reason']);

        return redirect()->back()->with('success', 'Expense rejected successfully.');
    }

    /**
     * Mark as paid
     */
    public function markPaid(Expense $expense)
    {
        if ($expense->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved expenses can be marked as paid.');
        }
        
        $expense->update([
            'status' => 'paid',
        ]);

        return redirect()->back()
            ->with('success', 'Expense marked as paid.');
    }

    /**
     * Send approval request to Managers
     */
    private function sendApprovalRequestToManagers(Expense $expense)
    {
        try {
            $managers = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['PROJECT MANAGER', 'SALES MANAGER']);
            })->get();

            foreach ($managers as $manager) {
                Notification::create([
                    'user_id' => $manager->id,
                    'title' => 'Expense Approval Request',
                    'message' => "New expense request from {$expense->creator->name} requires your approval.",
                    'type' => 'approval',
                    'data' => [
                        'expense_id' => $expense->id,
                        'expense_number' => $expense->expense_number,
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send Manager approval notification', [
                'expense_id' => $expense->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send approval request to HR
     */
    private function sendApprovalRequestToHR(Expense $expense)
    {
        try {
            $hrManagers = User::whereHas('roles', function($q) {
                $q->where('name', 'HR MANAGER');
            })->get();

            foreach ($hrManagers as $hr) {
                Notification::create([
                    'user_id' => $hr->id,
                    'title' => 'Expense Approval Request',
                    'message' => "Expense {$expense->expense_number} requires HR approval.",
                    'type' => 'approval',
                    'data' => [
                        'expense_id' => $expense->id,
                        'expense_number' => $expense->expense_number,
                    ]
                ]);
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
    private function sendApprovalRequestToAdmin(Expense $expense)
    {
        try {
            $admins = User::whereHas('roles', function($q) {
                $q->where('name', 'SUPER ADMIN');
            })->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Expense Final Approval Request',
                    'message' => "Expense {$expense->expense_number} requires final admin approval.",
                    'type' => 'approval',
                    'data' => [
                        'expense_id' => $expense->id,
                        'expense_number' => $expense->expense_number,
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send Admin approval notification', [
                'expense_id' => $expense->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify creator of approval
     */
    private function notifyCreatorOfApproval(Expense $expense)
    {
        try {
            Notification::create([
                'user_id' => $expense->created_by,
                'title' => 'Expense Approved',
                'message' => "Your expense request {$expense->expense_number} has been fully approved.",
                'type' => 'success',
                'data' => [
                    'expense_id' => $expense->id,
                    'expense_number' => $expense->expense_number,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify creator of approval', [
                'expense_id' => $expense->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify creator of rejection
     */
    private function notifyCreatorOfRejection(Expense $expense, $reason)
    {
        try {
            Notification::create([
                'user_id' => $expense->created_by,
                'title' => 'Expense Rejected',
                'message' => "Your expense request {$expense->expense_number} has been rejected. Reason: " . \Illuminate\Support\Str::limit($reason, 100),
                'type' => 'error',
                'data' => [
                    'expense_id' => $expense->id,
                    'expense_number' => $expense->expense_number,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify creator of rejection', [
                'expense_id' => $expense->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
