<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advance;
use App\Models\Project;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Traits\HandlesDeletionApproval;

class AdvanceController extends Controller
{
    use HandlesDeletionApproval;
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Advance::with(['employee', 'vendor', 'project', 'creator', 'approver', 'managerApprover', 'hrApprover']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('advance_type')) {
            $query->where('advance_type', $request->advance_type);
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('advance_number', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by approval level (for HR and Admin to see pending approvals)
        if ($request->filled('approval_level')) {
            $query->where('approval_level', $request->approval_level);
        }

        // If not admin/HR, show only own advances (for employees)
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('HR MANAGER')) {
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('employee_id', $user->id);
            });
        }

        $advances = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::where('status', '!=', 'cancelled')->get();
        $employees = User::where('is_active', true)->get();
        $vendors = Vendor::where('status', 'active')->get();

        // Calculate user-specific stats
        $statsQuery = Advance::query();
        
        // Apply same filter as main query - if not admin/HR, show only own advances
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('HR MANAGER')) {
            $statsQuery->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('employee_id', $user->id);
            });
        }

        $stats = [
            'total' => $statsQuery->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'pending_hr' => (clone $statsQuery)->where('status', 'pending')->where('approval_level', 'hr')->count(),
            'pending_admin' => (clone $statsQuery)->where('status', 'pending')->where('approval_level', 'admin')->count(),
            'approved' => (clone $statsQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $statsQuery)->where('status', 'rejected')->count(),
            'settled' => (clone $statsQuery)->where('status', 'settled')->count(),
            'total_amount' => (clone $statsQuery)->sum('amount'),
        ];

        return view('advances.index', compact('advances', 'projects', 'employees', 'vendors', 'stats'));
    }

    public function create()
    {
        $projects = Project::where('status', '!=', 'cancelled')->get();
        $employees = User::where('is_active', true)->get();
        $vendors = Vendor::where('status', 'active')->get();
        return view('advances.create', compact('projects', 'employees', 'vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'advance_type' => 'required|in:employee,vendor,project',
            'employee_id' => 'required_if:advance_type,employee|nullable|exists:users,id',
            'vendor_id' => 'required_if:advance_type,vendor|nullable|exists:vendors,id',
            'project_id' => 'nullable|exists:projects,id',
            'amount' => 'required|numeric|min:0.01',
            'advance_date' => 'required|date',
            'expected_settlement_date' => 'nullable|date|after:advance_date',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = auth()->user();
        $validated['created_by'] = $user->id;
        $validated['status'] = 'pending';
        $validated['approval_level'] = 'hr'; // Start with HR approval

        $advance = Advance::create($validated);

        // Send notification to HR for first-level approval
        $this->sendApprovalRequestToHR($advance);

        return redirect()->route('advances.index')
            ->with('success', 'Advance created successfully. Pending HR approval.');
    }

    public function show(Advance $advance)
    {
        $advance->load(['employee', 'vendor', 'project', 'creator', 'approver', 'managerApprover', 'hrApprover']);
        return view('advances.show', compact('advance'));
    }

    public function edit(Advance $advance)
    {
        if ($advance->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot edit approved or rejected advances.');
        }

        $projects = Project::where('status', '!=', 'cancelled')->get();
        $employees = User::where('is_active', true)->get();
        $vendors = Vendor::where('status', 'active')->get();
        return view('advances.edit', compact('advance', 'projects', 'employees', 'vendors'));
    }

    public function update(Request $request, Advance $advance)
    {
        if ($advance->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot edit approved or rejected advances.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'advance_type' => 'required|in:employee,vendor,project',
            'employee_id' => 'required_if:advance_type,employee|nullable|exists:users,id',
            'vendor_id' => 'required_if:advance_type,vendor|nullable|exists:vendors,id',
            'project_id' => 'nullable|exists:projects,id',
            'amount' => 'required|numeric|min:0.01',
            'advance_date' => 'required|date',
            'expected_settlement_date' => 'nullable|date|after:advance_date',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $advance->update($validated);

        return redirect()->route('advances.index')
            ->with('success', 'Advance updated successfully.');
    }

    public function destroy(Request $request, Advance $advance)
    {
        if ($advance->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot delete approved or rejected advances.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ], [
            'reason.required' => 'Please provide a reason for deletion.',
            'reason.min' => 'Reason must be at least 10 characters long.',
        ]);

        $modelName = 'Advance: ' . $advance->advance_number . ' - ' . $advance->title;
        return $this->handleDeletion($advance, $modelName, $validated['reason'], 'advances.index');
    }

    public function approve(Advance $advance)
    {
        $user = auth()->user();
        
        if ($advance->status !== 'pending') {
            return redirect()->back()->with('error', 'This advance has already been processed.');
        }

        // Multi-level approval flow: HR → Admin
        if ($advance->approval_level === 'hr') {
            // HR approval - Only HR Manager can approve
            if (!$user->hasRole('HR MANAGER')) {
                return redirect()->back()->with('error', 'Only HR Manager can approve at this level.');
            }
            
            $advance->update([
                'approval_level' => 'admin',
                'hr_approved_by' => $user->id,
                'hr_approved_at' => now(),
            ]);
            
            // Send notification to Admin
            $this->sendApprovalRequestToAdmin($advance);
            
            return redirect()->back()->with('success', 'HR approval granted. Forwarded to Admin for final approval.');
            
        } elseif ($advance->approval_level === 'admin') {
            // Admin final approval
            if (!$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only Admin can provide final approval.');
            }
            
            $advance->update([
                'status' => 'approved',
                'approval_level' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
            
            // Notify creator
            $this->notifyCreatorOfApproval($advance);
            
            return redirect()->back()->with('success', 'Advance fully approved and finalized.');
        }

        return redirect()->back()->with('error', 'Invalid approval level.');
    }

    public function reject(Request $request, Advance $advance)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($advance->status !== 'pending') {
            return redirect()->back()->with('error', 'This advance has already been processed.');
        }

        // Multi-level rejection based on current approval level
        if ($advance->approval_level === 'hr') {
            if (!$user->hasRole('HR MANAGER')) {
                return redirect()->back()->with('error', 'Only HR Manager can reject at this level.');
            }
            
            $advance->update([
                'status' => 'rejected',
                'approval_level' => 'rejected',
                'hr_rejection_reason' => $validated['rejection_reason'],
            ]);
            
        } elseif ($advance->approval_level === 'admin') {
            if (!$user->hasRole('SUPER ADMIN')) {
                return redirect()->back()->with('error', 'Only Admin can reject at this level.');
            }
            
            $advance->update([
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
        $this->notifyCreatorOfRejection($advance, $validated['rejection_reason']);

        return redirect()->back()->with('success', 'Advance rejected.');
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
    private function sendApprovalRequestToHR(Advance $advance)
    {
        try {
            $hrManagers = \App\Models\User::whereHas('roles', function($q) {
                $q->where('name', 'HR MANAGER');
            })->get();

            foreach ($hrManagers as $hr) {
                // Send email notification
                // Mail::to($hr->email)->send(new AdvanceApprovalNotification($advance));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send HR approval notification', [
                'advance_id' => $advance->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send approval request to Managers
     */
    private function sendApprovalRequestToManagers(Advance $advance)
    {
        try {
            $managers = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['PROJECT MANAGER', 'SALES MANAGER']);
            })->get();

            foreach ($managers as $manager) {
                Notification::create([
                    'user_id' => $manager->id,
                    'title' => 'Advance Approval Request',
                    'message' => "New advance request from {$advance->creator->name} requires your approval.",
                    'type' => 'approval',
                    'data' => [
                        'advance_id' => $advance->id,
                        'advance_number' => $advance->advance_number,
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send Manager approval notification', [
                'advance_id' => $advance->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send approval request to Admin
     */
    private function sendApprovalRequestToAdmin(Advance $advance)
    {
        try {
            $admins = User::whereHas('roles', function($q) {
                $q->where('name', 'SUPER ADMIN');
            })->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Advance Final Approval Request',
                    'message' => "Advance {$advance->advance_number} requires final admin approval.",
                    'type' => 'approval',
                    'data' => [
                        'advance_id' => $advance->id,
                        'advance_number' => $advance->advance_number,
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send Admin approval notification', [
                'advance_id' => $advance->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify creator of approval
     */
    private function notifyCreatorOfApproval(Advance $advance)
    {
        try {
            Notification::create([
                'user_id' => $advance->created_by,
                'title' => 'Advance Approved',
                'message' => "Your advance request {$advance->advance_number} has been fully approved.",
                'type' => 'success',
                'data' => [
                    'advance_id' => $advance->id,
                    'advance_number' => $advance->advance_number,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify creator of approval', [
                'advance_id' => $advance->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify creator of rejection
     */
    private function notifyCreatorOfRejection(Advance $advance, $reason)
    {
        try {
            Notification::create([
                'user_id' => $advance->created_by,
                'title' => 'Advance Rejected',
                'message' => "Your advance request {$advance->advance_number} has been rejected. Reason: " . \Illuminate\Support\Str::limit($reason, 100),
                'type' => 'error',
                'data' => [
                    'advance_id' => $advance->id,
                    'advance_number' => $advance->advance_number,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify creator of rejection', [
                'advance_id' => $advance->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
