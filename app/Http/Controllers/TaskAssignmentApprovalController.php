<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskAssignmentApproval;
use App\Models\Task;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskAssignmentApprovalController extends Controller
{
    /**
     * Display a listing of task assignment approvals
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Only SUPER ADMIN and SALES MANAGER can view assignment approvals
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view task assignment approvals.');
        }
        
        $query = TaskAssignmentApproval::with(['task.project', 'requester', 'assignedTo', 'managerApprover', 'adminApprover'])
            ->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default: show pending requests
            $query->whereIn('status', ['pending_manager_approval', 'pending_admin_approval']);
        }
        
        $approvals = $query->paginate(15);
        
        return view('admin.task-assignment-approvals.index', compact('approvals'));
    }

    /**
     * Display the specified assignment approval
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Only SUPER ADMIN and SALES MANAGER can view assignment approvals
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view task assignment approvals.');
        }
        
        $approval = TaskAssignmentApproval::with(['task.project', 'requester', 'assignedTo', 'managerApprover', 'adminApprover'])
            ->findOrFail($id);
        
        return view('admin.task-assignment-approvals.show', compact('approval'));
    }

    /**
     * Approve a task assignment
     */
    public function approve($id)
    {
        $user = Auth::user();
        $approval = TaskAssignmentApproval::findOrFail($id);
        
        if ($approval->isPendingAtManagerLevel()) {
            // Manager approval
            if (!$user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
                abort(403, 'You do not have permission to approve this assignment at the manager level.');
            }
            
            $approval->update([
                'status' => 'pending_admin_approval',
                'manager_approved_by' => $user->id,
                'manager_approved_at' => now(),
            ]);
            $this->sendApprovalRequestToAdmin($approval);
            $message = 'Task assignment approved by manager. Awaiting admin approval.';
        } elseif ($approval->isPendingAtAdminLevel()) {
            // Admin approval
            if (!$user->hasRole('SUPER ADMIN')) {
                abort(403, 'You do not have permission to approve this assignment at the admin level.');
            }
            
            DB::beginTransaction();
            try {
                $task = $approval->task;
                $task->update([
                    'assigned_to' => $approval->assigned_to,
                    'updated_at' => now(),
                ]);
                
                $approval->update([
                    'status' => 'approved',
                    'admin_approved_by' => $user->id,
                    'admin_approved_at' => now(),
                ]);
                DB::commit();
                
                $this->notifyRequesterOfApproval($approval);
                $this->notifyAssignee($approval);
                $message = 'Task assignment approved by admin. Task assigned successfully.';
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error approving task assignment by admin: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Error approving assignment: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'This approval has already been processed.');
        }
        
        return redirect()->route('admin.task-assignment-approvals.index')
            ->with('success', $message);
    }

    /**
     * Show the reject form
     */
    public function showRejectForm($id)
    {
        $user = Auth::user();
        $approval = TaskAssignmentApproval::with(['task.project', 'requester', 'assignedTo'])
            ->findOrFail($id);
        
        // Authorization check
        $canReject = false;
        if ($approval->isPendingAtManagerLevel() && ($user->hasRole('SALES MANAGER') || $user->hasRole('SUPER ADMIN'))) {
            $canReject = true;
        } elseif ($approval->isPendingAtAdminLevel() && $user->hasRole('SUPER ADMIN')) {
            $canReject = true;
        }
        
        if (!$canReject) {
            abort(403, 'You do not have permission to reject this assignment.');
        }
        
        return view('admin.task-assignment-approvals.reject', compact('approval'));
    }

    /**
     * Reject a task assignment
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $approval = TaskAssignmentApproval::findOrFail($id);
        
        // Authorization check
        $canReject = false;
        if ($approval->isPendingAtManagerLevel() && ($user->hasRole('SALES MANAGER') || $user->hasRole('SUPER ADMIN'))) {
            $canReject = true;
        } elseif ($approval->isPendingAtAdminLevel() && $user->hasRole('SUPER ADMIN')) {
            $canReject = true;
        }
        
        if (!$canReject) {
            abort(403, 'You do not have permission to reject this assignment.');
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        if ($approval->isPendingAtManagerLevel()) {
            $approval->update([
                'status' => 'rejected',
                'manager_rejection_reason' => $request->rejection_reason,
                'manager_approved_by' => $user->id,
                'manager_approved_at' => now(),
            ]);
            $this->notifyRequesterOfRejection($approval);
            $message = 'Task assignment rejected by manager.';
        } elseif ($approval->isPendingAtAdminLevel()) {
            $approval->update([
                'status' => 'rejected',
                'admin_rejection_reason' => $request->rejection_reason,
                'admin_approved_by' => $user->id,
                'admin_approved_at' => now(),
            ]);
            $this->notifyRequesterOfRejection($approval);
            $message = 'Task assignment rejected by admin.';
        } else {
            return redirect()->back()->with('error', 'This approval has already been processed.');
        }
        
        return redirect()->route('admin.task-assignment-approvals.index')
            ->with('success', $message);
    }
    
    // Helper methods for notifications
    private function sendApprovalRequestToAdmin(TaskAssignmentApproval $approval)
    {
        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'SUPER ADMIN');
        })->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Task Assignment Approval Required',
                'message' => "Task '{$approval->task->title}' assignment has been approved by manager. Admin approval required.",
                'type' => 'approval',
                'data' => [
                    'task_assignment_approval_id' => $approval->id,
                    'task_id' => $approval->task->id,
                ]
            ]);
        }
    }
    
    private function notifyRequesterOfApproval(TaskAssignmentApproval $approval)
    {
        Notification::create([
            'user_id' => $approval->requested_by,
            'title' => 'Task Assignment Approved',
            'message' => "Your request to assign task '{$approval->task->title}' to {$approval->assignedTo->name} has been approved.",
            'type' => 'success',
            'data' => [
                'task_assignment_approval_id' => $approval->id,
                'task_id' => $approval->task->id,
            ]
        ]);
    }
    
    private function notifyRequesterOfRejection(TaskAssignmentApproval $approval)
    {
        $rejectionReason = $approval->admin_rejection_reason ?? $approval->manager_rejection_reason;
        Notification::create([
            'user_id' => $approval->requested_by,
            'title' => 'Task Assignment Rejected',
            'message' => "Your request to assign task '{$approval->task->title}' has been rejected. Reason: " . Str::limit($rejectionReason, 100),
            'type' => 'error',
            'data' => [
                'task_assignment_approval_id' => $approval->id,
                'task_id' => $approval->task->id,
            ]
        ]);
    }
    
    private function notifyAssignee(TaskAssignmentApproval $approval)
    {
        Notification::create([
            'user_id' => $approval->assigned_to,
            'title' => 'Task Assigned to You',
            'message' => "Task '{$approval->task->title}' has been assigned to you.",
            'type' => 'task',
            'data' => [
                'task_assignment_approval_id' => $approval->id,
                'task_id' => $approval->task->id,
            ]
        ]);
    }
}
