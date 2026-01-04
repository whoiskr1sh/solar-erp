<?php

namespace App\Http\Controllers;

use App\Models\LeadReassignmentRequest;
use App\Models\Lead;
use App\Models\LeadContactView;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class LeadReassignmentRequestController extends Controller
{
    /**
     * Display a listing of reassignment requests
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Only SUPER ADMIN and SALES MANAGER can view requests
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view reassignment requests.');
        }
        
        $query = LeadReassignmentRequest::with(['requester', 'assignedTo', 'managerApprover', 'adminApprover'])
            ->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default: show pending requests
            $query->whereIn('status', ['pending_manager_approval', 'pending_admin_approval']);
        }
        
        // Sales Manager can only view requests from sub coordinators
        if ($user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
            $query->whereHas('requester', function($q) {
                $q->whereHas('roles', function($roleQuery) {
                    $roleQuery->whereIn('name', ['TELE SALES', 'FIELD SALES']);
                });
            });
        }
        
        $requests = $query->paginate(15);
        
        return view('admin.lead-reassignment-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new reassignment request
     */
    public function create()
    {
        try {
        $user = Auth::user();
        
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to continue.');
            }
            
            // Sales Manager and Admin cannot request reassignment - they only approve
            if ($user->hasRole('SALES MANAGER') || $user->hasRole('SUPER ADMIN')) {
                return redirect()->route('leads.index')->with('error', 'You cannot request reassignment. You can only approve reassignment requests.');
            }
            
            // Get leads where user has viewed the contact number
            // Exclude already reassigned leads - they should not appear in the selection
            $viewedLeadIds = LeadContactView::where('user_id', $user->id)->pluck('lead_id')->toArray();
            $assignedLeads = Lead::whereIn('id', $viewedLeadIds)
                ->where('is_reassigned', false)
                ->get();
            $assignedLeadsCount = $assignedLeads->count();
            
            // Get all active users except the current user
        $users = User::where('is_active', true)
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();
        
            $reassignmentTargetRole = 'User';
            
            // Ensure assignedLeads is always a collection (even if empty)
            if (!$assignedLeads) {
                $assignedLeads = collect();
            }
            
            // Check if there's already a pending request
            $hasPendingRequest = LeadReassignmentRequest::where('requested_by', $user->id)
                ->whereIn('status', ['pending_manager_approval', 'pending_admin_approval'])
                ->exists();
            
            return view('leads.request-reassignment', compact('users', 'assignedLeads', 'assignedLeadsCount', 'hasPendingRequest', 'reassignmentTargetRole'));
        } catch (\Exception $e) {
            \Log::error('Error in LeadReassignmentRequestController::create: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('leads.index')->with('error', 'An error occurred while loading the reassignment request page. Please try again.');
        }
    }

    /**
     * Store a newly created reassignment request
     */
    public function store(Request $request)
    {
        try {
        $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to continue.');
            }
            
            // Sales Manager and Admin cannot request reassignment - they only approve
            if ($user->hasRole('SALES MANAGER') || $user->hasRole('SUPER ADMIN')) {
                return redirect()->route('leads.index')->with('error', 'You cannot request reassignment. You can only approve reassignment requests.');
            }
            
            // Log incoming request data for debugging
            \Log::info('Lead Reassignment Request Data:', [
                'selection_type' => $request->selection_type,
                'selected_lead_ids' => $request->selected_lead_ids,
                'leads_count' => $request->leads_count,
                'assigned_to' => $request->assigned_to,
                'has_reason' => !empty($request->reason),
            ]);
        
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'reason' => 'required|string|max:1000',
                'selection_type' => 'required|in:count,specific',
                'leads_count' => 'required_if:selection_type,count|nullable|integer|min:1',
                'selected_lead_ids' => 'required_if:selection_type,specific|nullable|array|min:1',
                'selected_lead_ids.*' => 'exists:leads,id',
            ]);
            
            // Prevent self-reassignment
            if ($request->assigned_to == $user->id) {
                return redirect()->back()->with('error', 'You cannot reassign leads to yourself.')->withInput();
            }
            
            // Check if assigned user exists and is active
            $assignedUser = User::find($request->assigned_to);
            if (!$assignedUser || !$assignedUser->is_active) {
                return redirect()->back()->with('error', 'Selected user is not available for assignment.')->withInput();
            }
            
            // All users can assign to any active user - no role restriction
            
            // Get leads where user has viewed the contact number
            // Exclude already reassigned leads - they should not appear in the selection
            $viewedLeadIds = LeadContactView::where('user_id', $user->id)->pluck('lead_id')->toArray();
            $userLeads = Lead::whereIn('id', $viewedLeadIds)
                ->where('is_reassigned', false)
                ->get();
            $totalLeadsCount = $userLeads->count();
            
            if ($totalLeadsCount === 0) {
                return redirect()->back()->with('error', 'You have not viewed any lead contact numbers yet. Please view a lead\'s contact number first before requesting reassignment.')->withInput();
            }
            
            // Validate lead selection
            $selectedLeadIds = [];
            $leadsCount = null;
            
            if ($request->selection_type === 'specific') {
                $selectedLeadIds = $request->selected_lead_ids ?? [];
                
                \Log::info('Processing specific leads selection:', [
                    'raw_selected_lead_ids' => $selectedLeadIds,
                    'is_array' => is_array($selectedLeadIds),
                    'count' => is_array($selectedLeadIds) ? count($selectedLeadIds) : 0,
                ]);
                
                // Validate that at least one lead is selected
                if (empty($selectedLeadIds) || !is_array($selectedLeadIds)) {
                    \Log::warning('No leads selected or not an array');
                    return redirect()->back()->with('error', 'Please select at least one lead to reassign.')->withInput();
                }
                
                // Remove any empty values and ensure all are integers
                $selectedLeadIds = array_values(array_filter(array_map('intval', $selectedLeadIds)));
                
                \Log::info('Processed selected lead IDs:', ['ids' => $selectedLeadIds]);
                
                if (empty($selectedLeadIds)) {
                    \Log::warning('Selected lead IDs empty after processing');
                    return redirect()->back()->with('error', 'Please select at least one valid lead to reassign.')->withInput();
                }
                
                // Verify all selected leads have been viewed by the user and are not already reassigned
                $validLeads = Lead::whereIn('id', $selectedLeadIds)
                    ->whereIn('id', $viewedLeadIds)
                    ->where('is_reassigned', false)
                    ->pluck('id')
                    ->toArray();
                
                if (count($validLeads) !== count($selectedLeadIds)) {
                    $invalidCount = count($selectedLeadIds) - count($validLeads);
                    return redirect()->back()->with('error', "{$invalidCount} of the selected leads are invalid or you have not viewed their contact numbers. Please select only leads you have viewed.")->withInput();
                }
                
                $leadsCount = count($selectedLeadIds);
            } else {
                // Count-based selection
                $leadsCount = (int) $request->leads_count;
                if ($leadsCount > $totalLeadsCount) {
                    return redirect()->back()->with('error', "You can only reassign up to {$totalLeadsCount} lead(s).")->withInput();
                }
                if ($leadsCount < 1) {
                    return redirect()->back()->with('error', 'You must reassign at least 1 lead.')->withInput();
                }
        }
        
        // Check if there's already a pending request
        $existingRequest = LeadReassignmentRequest::where('requested_by', $user->id)
                ->whereIn('status', ['pending_manager_approval', 'pending_admin_approval'])
            ->exists();
        
        if ($existingRequest) {
                return redirect()->back()->with('error', 'You already have a pending reassignment request. Please wait for approval.')->withInput();
        }
            
            // All requests go to Sales Manager first (since Sales Manager and Admin can't request)
            $initialStatus = 'pending_manager_approval';
        
        // Create the reassignment request
        $reassignmentRequest = LeadReassignmentRequest::create([
            'requested_by' => $user->id,
            'assigned_to' => $request->assigned_to,
            'reason' => $request->reason,
                'status' => $initialStatus,
                'selected_lead_ids' => ($request->selection_type === 'specific' && !empty($selectedLeadIds)) ? $selectedLeadIds : null,
                'leads_count' => $leadsCount,
            ]);
            
            // Notify Sales Managers
            $this->sendApprovalRequestToSalesManager($reassignmentRequest, $leadsCount);
        
        return redirect()->route('leads.index')
            ->with('success', 'Reassignment request submitted successfully! You will be notified once it\'s approved or rejected.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error in LeadReassignmentRequestController::store: ' . $e->getMessage());
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
        
        // Only SUPER ADMIN and SALES MANAGER can view requests
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view reassignment requests.');
        }
        
        $reassignmentRequest = LeadReassignmentRequest::with(['requester', 'assignedTo', 'managerApprover', 'adminApprover'])
            ->findOrFail($id);
        
        // Sales Manager can only view requests from sub coordinators
        if ($user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
            $requester = $reassignmentRequest->requester;
            if (!$requester->hasRole('TELE SALES') && !$requester->hasRole('FIELD SALES')) {
                abort(403, 'You do not have permission to view this request.');
            }
        }
        
        // Get leads to be reassigned (leads where requester viewed the contact)
        // Exclude already reassigned leads
        $requesterViewedLeadIds = LeadContactView::where('user_id', $reassignmentRequest->requested_by)->pluck('lead_id')->toArray();
        
        if ($reassignmentRequest->selected_lead_ids && is_array($reassignmentRequest->selected_lead_ids) && !empty($reassignmentRequest->selected_lead_ids)) {
            // Get specific selected leads
            $selectedIds = is_array($reassignmentRequest->selected_lead_ids) ? $reassignmentRequest->selected_lead_ids : [];
            $leads = Lead::whereIn('id', $selectedIds)
                ->whereIn('id', $requesterViewedLeadIds)
                ->where('is_reassigned', false)
            ->with(['assignedUser', 'creator'])
            ->get();
        } else {
            // Get first N leads if count-based selection
            $leads = Lead::whereIn('id', $requesterViewedLeadIds)
                ->where('is_reassigned', false)
                ->with(['assignedUser', 'creator'])
                ->limit($reassignmentRequest->leads_count ?? 999999)
                ->get();
        }
        
        return view('admin.lead-reassignment-requests.show', compact('reassignmentRequest', 'leads'));
    }

    /**
     * Approve a reassignment request
     */
    public function approve($id)
    {
        $user = Auth::user();
        $reassignmentRequest = LeadReassignmentRequest::findOrFail($id);
        
        if ($reassignmentRequest->isPendingAtManagerLevel()) {
            // Manager approval
            if (!$user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
                abort(403, 'You do not have permission to approve this request at the manager level.');
            }
            
            $reassignmentRequest->update([
                'status' => 'pending_admin_approval',
                'manager_approved_by' => $user->id,
                'manager_approved_at' => now(),
            ]);
            $this->sendApprovalRequestToAdmin($reassignmentRequest, $reassignmentRequest->leads_count);
            $message = 'Lead reassignment request approved by manager. Awaiting admin approval.';
        } elseif ($reassignmentRequest->isPendingAtAdminLevel()) {
            // Admin approval
            if (!$user->hasRole('SUPER ADMIN')) {
                abort(403, 'You do not have permission to approve this request at the admin level.');
        }
        
        DB::beginTransaction();
        try {
                // Get leads to reassign (leads where requester viewed the contact)
                // Exclude already reassigned leads
                $requesterViewedLeadIds = LeadContactView::where('user_id', $reassignmentRequest->requested_by)->pluck('lead_id')->toArray();
                
                if ($reassignmentRequest->selected_lead_ids && is_array($reassignmentRequest->selected_lead_ids) && !empty($reassignmentRequest->selected_lead_ids)) {
                    // Reassign specific leads (verify they were viewed by requester and not already reassigned)
                    $selectedIds = is_array($reassignmentRequest->selected_lead_ids) ? $reassignmentRequest->selected_lead_ids : [];
                    
                    // Ensure all IDs are integers
                    $selectedIds = array_map('intval', $selectedIds);
                    
                    // Get valid lead IDs that were viewed by requester and are not already reassigned
                    $nonReassignedLeadIds = Lead::whereIn('id', $requesterViewedLeadIds)
                        ->where('is_reassigned', false)
                        ->pluck('id')
                        ->toArray();
                    $validLeadIds = array_intersect($selectedIds, $nonReassignedLeadIds);
                    
                    if (empty($validLeadIds)) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'None of the selected leads are valid or were viewed by the requester.')->withInput();
                    }
                    
                    // Reassign only the specific selected leads
                    $leadsUpdated = Lead::whereIn('id', $validLeadIds)
                ->update([
                    'assigned_user_id' => $reassignmentRequest->assigned_to,
                    'last_updated_by' => $user->id,
                            'is_reassigned' => true,
                        ]);
                    
                    if ($leadsUpdated === 0) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Failed to reassign the selected leads. Please try again.')->withInput();
                    }
                } else {
                    // Reassign first N leads based on count (from viewed leads, excluding already reassigned)
                    $leadsToReassign = Lead::whereIn('id', $requesterViewedLeadIds)
                        ->where('is_reassigned', false)
                        ->limit($reassignmentRequest->leads_count)
                        ->pluck('id')
                        ->toArray();
                    
                    $leadsUpdated = Lead::whereIn('id', $leadsToReassign)
                        ->update([
                            'assigned_user_id' => $reassignmentRequest->assigned_to,
                            'last_updated_by' => $user->id,
                            'is_reassigned' => true,
                        ]);
                }
                
            $reassignmentRequest->update([
                'status' => 'approved',
                    'admin_approved_by' => $user->id,
                    'admin_approved_at' => now(),
            ]);
            DB::commit();
            
                $this->notifyRequesterOfApproval($reassignmentRequest, $leadsUpdated);
                $this->notifyNewAssignee($reassignmentRequest, $leadsUpdated);
                $message = "Lead reassignment request approved by admin. {$leadsUpdated} lead(s) have been reassigned.";
        } catch (\Exception $e) {
            DB::rollBack();
                \Log::error('Error approving lead reassignment request by admin: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error approving request: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        return redirect()->route('admin.lead-reassignment-requests.index')
            ->with('success', $message);
    }

    /**
     * Show the reject form
     */
    public function showRejectForm($id)
    {
        $user = Auth::user();
        $reassignmentRequest = LeadReassignmentRequest::with(['requester', 'assignedTo'])
            ->findOrFail($id);
        
        // Authorization check
        $canReject = false;
        if ($reassignmentRequest->isPendingAtManagerLevel() && ($user->hasRole('SALES MANAGER') || $user->hasRole('SUPER ADMIN'))) {
            $canReject = true;
        } elseif ($reassignmentRequest->isPendingAtAdminLevel() && $user->hasRole('SUPER ADMIN')) {
            $canReject = true;
        }
        
        if (!$canReject) {
            abort(403, 'You do not have permission to reject this request.');
        }
        
        return view('admin.lead-reassignment-requests.reject', compact('reassignmentRequest'));
    }

    /**
     * Reject a reassignment request
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $reassignmentRequest = LeadReassignmentRequest::findOrFail($id);
        
        // Authorization check
        $canReject = false;
        if ($reassignmentRequest->isPendingAtManagerLevel() && ($user->hasRole('SALES MANAGER') || $user->hasRole('SUPER ADMIN'))) {
            $canReject = true;
        } elseif ($reassignmentRequest->isPendingAtAdminLevel() && $user->hasRole('SUPER ADMIN')) {
            $canReject = true;
        }
        
        if (!$canReject) {
            abort(403, 'You do not have permission to reject this request.');
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        if ($reassignmentRequest->isPendingAtManagerLevel()) {
            $reassignmentRequest->update([
                'status' => 'rejected',
                'manager_rejection_reason' => $request->rejection_reason,
                'manager_approved_by' => $user->id,
                'manager_approved_at' => now(),
            ]);
            $this->notifyRequesterOfRejection($reassignmentRequest);
            $message = 'Lead reassignment request rejected by manager.';
        } elseif ($reassignmentRequest->isPendingAtAdminLevel()) {
        $reassignmentRequest->update([
            'status' => 'rejected',
                'admin_rejection_reason' => $request->rejection_reason,
                'admin_approved_by' => $user->id,
                'admin_approved_at' => now(),
            ]);
            $this->notifyRequesterOfRejection($reassignmentRequest);
            $message = 'Lead reassignment request rejected by admin.';
        } else {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        return redirect()->route('admin.lead-reassignment-requests.index')
            ->with('success', $message);
    }
    
    // Helper methods for notifications
    private function sendApprovalRequestToSalesManager(LeadReassignmentRequest $reassignmentRequest, $leadsCount)
    {
        $managers = User::whereHas('roles', function ($q) {
            $q->where('name', 'SALES MANAGER');
        })->where('is_active', true)->get();
        
        foreach ($managers as $manager) {
            Notification::create([
                'user_id' => $manager->id,
                'title' => 'Lead Reassignment Approval Required',
                'message' => "{$reassignmentRequest->requester->name} has requested to reassign {$leadsCount} lead(s) to {$reassignmentRequest->assignedTo->name}. Manager approval required.",
                'type' => 'approval',
                'data' => [
                    'lead_reassignment_request_id' => $reassignmentRequest->id,
                    'requester_id' => $reassignmentRequest->requested_by,
                    'assigned_to_id' => $reassignmentRequest->assigned_to,
                ]
            ]);
        }
    }
    
    private function sendApprovalRequestToAdmin(LeadReassignmentRequest $reassignmentRequest, $leadsCount)
    {
        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'SUPER ADMIN');
        })->where('is_active', true)->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Lead Reassignment Approval Required',
                'message' => "Lead reassignment request from {$reassignmentRequest->requester->name} has been approved by manager. Admin approval required for {$leadsCount} lead(s).",
                'type' => 'approval',
                'data' => [
                    'lead_reassignment_request_id' => $reassignmentRequest->id,
                    'requester_id' => $reassignmentRequest->requested_by,
                    'assigned_to_id' => $reassignmentRequest->assigned_to,
                ]
            ]);
        }
    }
    
    private function notifyRequesterOfApproval(LeadReassignmentRequest $reassignmentRequest, $leadsUpdated)
    {
        Notification::create([
            'user_id' => $reassignmentRequest->requested_by,
            'title' => 'Lead Reassignment Approved',
            'message' => "Your request to reassign {$leadsUpdated} lead(s) to {$reassignmentRequest->assignedTo->name} has been approved.",
            'type' => 'success',
            'data' => [
                'lead_reassignment_request_id' => $reassignmentRequest->id,
            ]
        ]);
    }
    
    private function notifyRequesterOfRejection(LeadReassignmentRequest $reassignmentRequest)
    {
        $rejectionReason = $reassignmentRequest->admin_rejection_reason ?? $reassignmentRequest->manager_rejection_reason;
        Notification::create([
            'user_id' => $reassignmentRequest->requested_by,
            'title' => 'Lead Reassignment Rejected',
            'message' => "Your reassignment request has been rejected. Reason: " . Str::limit($rejectionReason, 100),
            'type' => 'error',
            'data' => [
                'lead_reassignment_request_id' => $reassignmentRequest->id,
            ]
        ]);
    }
    
    private function notifyNewAssignee(LeadReassignmentRequest $reassignmentRequest, $leadsUpdated)
    {
        // Get the actual lead names for better notification
        $requesterViewedLeadIds = LeadContactView::where('user_id', $reassignmentRequest->requested_by)->pluck('lead_id')->toArray();
        
        if ($reassignmentRequest->selected_lead_ids && is_array($reassignmentRequest->selected_lead_ids) && !empty($reassignmentRequest->selected_lead_ids)) {
            $leadIds = array_intersect($reassignmentRequest->selected_lead_ids, $requesterViewedLeadIds);
        } else {
            $leadIds = array_slice($requesterViewedLeadIds, 0, $reassignmentRequest->leads_count);
        }
        
        $leads = Lead::whereIn('id', $leadIds)->get(['id', 'name', 'company']);
        $leadNames = $leads->pluck('name')->take(5)->implode(', ');
        if ($leads->count() > 5) {
            $leadNames .= ' and ' . ($leads->count() - 5) . ' more';
        }
        
        Notification::create([
            'user_id' => $reassignmentRequest->assigned_to,
            'title' => 'Leads Reassigned to You',
            'message' => "{$leadsUpdated} lead(s) have been reassigned to you: {$leadNames}. Click to view them in Leads Management.",
            'type' => 'lead',
            'data' => [
                'lead_reassignment_request_id' => $reassignmentRequest->id,
                'lead_ids' => $leadIds,
                'redirect_url' => route('leads.index', ['assigned_to' => $reassignmentRequest->assigned_to]),
            ]
        ]);
    }
}
