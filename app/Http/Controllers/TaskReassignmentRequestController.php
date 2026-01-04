<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskReassignmentRequest;
use App\Models\Task;
use App\Models\User;
use App\Models\Notification;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskReassignmentRequestController extends Controller
{
    /**
     * Display a listing of reassignment requests
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // SALES MANAGER can see requests from sub coordinators
        // SUPER ADMIN can see all requests (including from Sales Manager)
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view reassignment requests.');
        }
        
        $query = TaskReassignmentRequest::with(['task', 'requester', 'assignedTo', 'approver'])
            ->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default: show pending requests
            $query->where('status', 'pending');
        }
        
        // SALES MANAGER only sees requests from sub coordinators
        if ($user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
            $query->whereHas('requester', function($q) {
                $q->whereHas('roles', function($roleQuery) {
                    $roleQuery->whereIn('name', ['PROJECT ENGINEER', 'LIASONING EXECUTIVE']);
                });
            });
        }
        
        $requests = $query->paginate(15);
        
        return view('admin.task-reassignment-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new reassignment request
     */
    public function create(Request $request, Task $task)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to continue.');
            }
            
            // Check if user has permission to request reassignment for this task
            $project = $task->project;
            if (!$project) {
                return redirect()->back()->with('error', 'Task does not belong to any project.');
            }
            
            $isAuthorized = false;
            $isSubCoordinator = false;
            $isSalesManager = false;
            
            // Check if user is project manager, project engineer, or liaisoning officer
            if ($project->project_manager_id == $user->id 
                || $project->project_engineer == $user->id 
                || $project->liaisoning_officer == $user->id) {
                $isAuthorized = true;
                
                // Check if user is a sub coordinator (project engineer or liaisoning officer)
                if ($project->project_engineer == $user->id || $project->liaisoning_officer == $user->id) {
                    $isSubCoordinator = true;
                }
            }
            
            // Check if user is Sales Manager
            if ($user->hasRole('SALES MANAGER')) {
                $isSalesManager = true;
                $isAuthorized = true;
            }
            
            if (!$isAuthorized) {
                return redirect()->back()->with('error', 'You do not have permission to request reassignment for this task.');
            }
            
            // Get users to assign to based on requester role
            $users = collect();
            
            if ($isSubCoordinator) {
                // Sub coordinators can only request to Sales Manager
                $users = User::whereHas('roles', function($q) {
                    $q->where('name', 'SALES MANAGER');
                })->where('is_active', true)->orderBy('name')->get();
            } elseif ($isSalesManager) {
                // Sales Manager can only request to Admin
                $users = User::whereHas('roles', function($q) {
                    $q->where('name', 'SUPER ADMIN');
                })->where('is_active', true)->orderBy('name')->get();
            }
            
            // Check if there's already a pending request for this task
            $hasPendingRequest = TaskReassignmentRequest::where('task_id', $task->id)
                ->where('status', 'pending')
                ->exists();
            
            return view('tasks.request-reassignment', compact('task', 'users', 'hasPendingRequest', 'isSubCoordinator', 'isSalesManager'));
        } catch (\Exception $e) {
            \Log::error('Error in TaskReassignmentRequestController::create: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'An error occurred while loading the reassignment request page. Please try again.');
        }
    }

    /**
     * Store a newly created reassignment request
     */
    public function store(Request $request, Task $task)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to continue.');
            }
            
            $request->validate([
                'assigned_to' => 'required|exists:users,id',
                'reason' => 'required|string|max:1000',
            ]);
            
            // Prevent self-reassignment
            if ($request->assigned_to == $user->id) {
                return redirect()->back()->with('error', 'You cannot reassign tasks to yourself.')->withInput();
            }
            
            // Check if assigned user exists and is active
            $assignedUser = User::find($request->assigned_to);
            if (!$assignedUser || !$assignedUser->is_active) {
                return redirect()->back()->with('error', 'Selected user is not available for assignment.')->withInput();
            }
            
            // Check authorization
            $project = $task->project;
            if (!$project) {
                return redirect()->back()->with('error', 'Task does not belong to any project.')->withInput();
            }
            
            $isAuthorized = false;
            $isSubCoordinator = false;
            $isSalesManager = false;
            
            if ($project->project_manager_id == $user->id 
                || $project->project_engineer == $user->id 
                || $project->liaisoning_officer == $user->id) {
                $isAuthorized = true;
                if ($project->project_engineer == $user->id || $project->liaisoning_officer == $user->id) {
                    $isSubCoordinator = true;
                }
            }
            
            if ($user->hasRole('SALES MANAGER')) {
                $isSalesManager = true;
                $isAuthorized = true;
            }
            
            if (!$isAuthorized) {
                return redirect()->back()->with('error', 'You do not have permission to request reassignment for this task.')->withInput();
            }
            
            // Validate assignment based on role
            if ($isSubCoordinator) {
                // Sub coordinators can only assign to Sales Manager
                if (!$assignedUser->hasRole('SALES MANAGER')) {
                    return redirect()->back()->with('error', 'You can only request reassignment to Sales Manager.')->withInput();
                }
            } elseif ($isSalesManager) {
                // Sales Manager can only assign to Admin
                if (!$assignedUser->hasRole('SUPER ADMIN')) {
                    return redirect()->back()->with('error', 'You can only request reassignment to Admin.')->withInput();
                }
            }
            
            // Check if there's already a pending request for this task
            $existingRequest = TaskReassignmentRequest::where('task_id', $task->id)
                ->where('status', 'pending')
                ->exists();
            
            if ($existingRequest) {
                return redirect()->back()->with('error', 'There is already a pending reassignment request for this task. Please wait for approval.')->withInput();
            }
            
            // Create the reassignment request
            $reassignmentRequest = TaskReassignmentRequest::create([
                'task_id' => $task->id,
                'requested_by' => $user->id,
                'assigned_to' => $request->assigned_to,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);
            
            // Notify approvers based on requester role
            if ($isSubCoordinator) {
                // Notify Sales Managers
                $managers = User::whereHas('roles', function($q) {
                    $q->where('name', 'SALES MANAGER');
                })->get();
            } elseif ($isSalesManager) {
                // Notify Admins
                $managers = User::whereHas('roles', function($q) {
                    $q->where('name', 'SUPER ADMIN');
                })->get();
            }
            
            foreach ($managers as $manager) {
                try {
                    Notification::create([
                        'user_id' => $manager->id,
                        'title' => 'Task Reassignment Request',
                        'message' => "{$user->name} has requested to reassign task '{$task->title}' to " . $assignedUser->name . ". Reason: " . Str::limit($request->reason, 100),
                        'type' => 'approval',
                        'data' => [
                            'reassignment_request_id' => $reassignmentRequest->id,
                            'task_id' => $task->id,
                            'requested_by' => $user->name,
                            'assigned_to' => $assignedUser->name,
                        ]
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error creating notification for manager ' . $manager->id . ': ' . $e->getMessage());
                }
            }
            
            return redirect()->route('project-manager.tasks.show', $task)
                ->with('success', 'Reassignment request submitted successfully! You will be notified once it\'s approved or rejected.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error in TaskReassignmentRequestController::store: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'An error occurred while submitting the reassignment request. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified reassignment request
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // SALES MANAGER can view requests from sub coordinators
        // SUPER ADMIN can view all requests
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view reassignment requests.');
        }
        
        $reassignmentRequest = TaskReassignmentRequest::with(['task.project', 'requester', 'assignedTo', 'approver'])
            ->findOrFail($id);
        
        // Check if Sales Manager can view this request
        if ($user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
            $requester = $reassignmentRequest->requester;
            if (!$requester->hasRole('PROJECT ENGINEER') && !$requester->hasRole('LIASONING EXECUTIVE')) {
                abort(403, 'You do not have permission to view this request.');
            }
        }
        
        return view('admin.task-reassignment-requests.show', compact('reassignmentRequest'));
    }

    /**
     * Approve a reassignment request
     */
    public function approve($id)
    {
        $user = Auth::user();
        
        // SALES MANAGER can approve requests from sub coordinators
        // SUPER ADMIN can approve all requests (including from Sales Manager)
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to approve reassignment requests.');
        }
        
        $reassignmentRequest = TaskReassignmentRequest::with(['task', 'requester', 'assignedTo'])->findOrFail($id);
        
        // Check if Sales Manager can approve this request
        if ($user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
            $requester = $reassignmentRequest->requester;
            if (!$requester->hasRole('PROJECT ENGINEER') && !$requester->hasRole('LIASONING EXECUTIVE')) {
                abort(403, 'You do not have permission to approve this request.');
            }
        }
        
        if ($reassignmentRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        DB::beginTransaction();
        try {
            // Reassign the task
            $task = $reassignmentRequest->task;
            $task->update([
                'assigned_to' => $reassignmentRequest->assigned_to,
            ]);
            
            // Update the request status
            $reassignmentRequest->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
            
            DB::commit();
            
            // Notify the requester
            Notification::create([
                'user_id' => $reassignmentRequest->requested_by,
                'title' => 'Task Reassignment Request Approved',
                'message' => "Your request to reassign task '{$task->title}' to " . $reassignmentRequest->assignedTo->name . " has been approved.",
                'type' => 'success',
                'data' => [
                    'reassignment_request_id' => $reassignmentRequest->id,
                    'task_id' => $task->id,
                ]
            ]);
            
            // Notify the new assignee
            Notification::create([
                'user_id' => $reassignmentRequest->assigned_to,
                'title' => 'Task Assigned to You',
                'message' => "Task '{$task->title}' has been reassigned to you from " . $reassignmentRequest->requester->name . ".",
                'type' => 'task',
                'data' => [
                    'reassignment_request_id' => $reassignmentRequest->id,
                    'task_id' => $task->id,
                ]
            ]);
            
            return redirect()->route('admin.task-reassignment-requests.index')
                ->with('success', "Reassignment request approved successfully! Task has been reassigned.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error approving task reassignment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error approving request: ' . $e->getMessage());
        }
    }

    /**
     * Show the reject form
     */
    public function showRejectForm($id)
    {
        $user = Auth::user();
        
        // SALES MANAGER can reject requests from sub coordinators
        // SUPER ADMIN can reject all requests
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to reject reassignment requests.');
        }
        
        $reassignmentRequest = TaskReassignmentRequest::with(['task', 'requester', 'assignedTo'])->findOrFail($id);
        
        // Check if Sales Manager can reject this request
        if ($user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
            $requester = $reassignmentRequest->requester;
            if (!$requester->hasRole('PROJECT ENGINEER') && !$requester->hasRole('LIASONING EXECUTIVE')) {
                abort(403, 'You do not have permission to reject this request.');
            }
        }
        
        if ($reassignmentRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        return view('admin.task-reassignment-requests.reject', compact('reassignmentRequest'));
    }

    /**
     * Reject a reassignment request
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        
        // SALES MANAGER can reject requests from sub coordinators
        // SUPER ADMIN can reject all requests
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to reject reassignment requests.');
        }
        
        $reassignmentRequest = TaskReassignmentRequest::with(['task', 'requester', 'assignedTo'])->findOrFail($id);
        
        // Check if Sales Manager can reject this request
        if ($user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
            $requester = $reassignmentRequest->requester;
            if (!$requester->hasRole('PROJECT ENGINEER') && !$requester->hasRole('LIASONING EXECUTIVE')) {
                abort(403, 'You do not have permission to reject this request.');
            }
        }
        
        if ($reassignmentRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection.',
            'rejection_reason.min' => 'Reason must be at least 10 characters long.',
        ]);
        
        $reassignmentRequest->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'rejection_reason' => $validated['rejection_reason'],
        ]);
        
        // Notify the requester
        Notification::create([
            'user_id' => $reassignmentRequest->requested_by,
            'title' => 'Task Reassignment Request Rejected',
            'message' => "Your request to reassign task '{$reassignmentRequest->task->title}' has been rejected. Reason: " . Str::limit($validated['rejection_reason'], 100),
            'type' => 'error',
            'data' => [
                'reassignment_request_id' => $reassignmentRequest->id,
                'task_id' => $reassignmentRequest->task->id,
                'rejection_reason' => $validated['rejection_reason'],
            ]
        ]);
        
        return redirect()->route('admin.task-reassignment-requests.index')
            ->with('success', 'Reassignment request rejected successfully.');
    }
}
