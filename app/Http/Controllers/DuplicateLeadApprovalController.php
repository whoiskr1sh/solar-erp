<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DuplicateLeadApproval;
use App\Models\Lead;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DuplicateLeadApprovalController extends Controller
{
    /**
     * Display a listing of duplicate lead approval requests
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can view duplicate lead approvals
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view duplicate lead approvals.');
        }
        
        $query = DuplicateLeadApproval::with(['requester', 'existingLead', 'approver'])
            ->orderBy('created_at', 'desc');
        
        // Filter by status - only apply filter if status is provided and not empty
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        // If status is empty or not provided, show all (no filter applied - this is the default)
        
        $approvals = $query->paginate(15);
        
        return view('admin.duplicate-lead-approvals.index', compact('approvals'));
    }

    /**
     * Display the specified duplicate lead approval
     */
    public function show($id)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can view duplicate lead approvals
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view duplicate lead approvals.');
        }
        
        $approval = DuplicateLeadApproval::with(['requester', 'existingLead', 'approver'])
            ->findOrFail($id);
        
        return view('admin.duplicate-lead-approvals.show', compact('approval'));
    }

    /**
     * Approve a duplicate lead request
     */
    public function approve($id)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can approve
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to approve duplicate lead requests.');
        }
        
        $approval = DuplicateLeadApproval::findOrFail($id);
        
        if ($approval->status !== 'pending') {
            return redirect()->back()->with('info', 'This approval request has already been processed.');
        }
        
        try {
            DB::beginTransaction();
            
            // Create the lead from the stored lead_data
            // Filter out non-fillable fields and system fields
            $leadData = $approval->lead_data;
            
            // Remove system fields that shouldn't be in lead_data
            $excludedFields = ['_token', '_method', 'duplicate_reason', 'csrf_token'];
            foreach ($excludedFields as $field) {
                unset($leadData[$field]);
            }
            
            // Only include fillable fields from Lead model
            $fillableFields = [
                'name', 'email', 'phone', 'company', 'address', 'source', 
                'status', 'priority', 'notes', 'estimated_value', 
                'expected_close_date', 'follow_up_date', 'follow_up_notes', 
                'last_follow_up_at', 'call_count', 'assigned_user_id', 
                'channel_partner_id', 'created_by', 'last_updated_by'
            ];
            
            $filteredLeadData = [];
            foreach ($fillableFields as $field) {
                if (isset($leadData[$field])) {
                    $filteredLeadData[$field] = $leadData[$field];
                }
            }
            
            // Set required fields
            $filteredLeadData['created_by'] = $approval->requested_by;
            
            // If status is INTERESTED or PARTIALLY INTERESTED, set follow-up date
            if (in_array($filteredLeadData['status'] ?? '', ['interested', 'partially_interested'])) {
                $filteredLeadData['follow_up_date'] = $filteredLeadData['follow_up_date'] ?? now()->addDays(10);
                $filteredLeadData['last_follow_up_at'] = $filteredLeadData['last_follow_up_at'] ?? now();
            }
            
            $lead = Lead::create($filteredLeadData);
            
            // Update approval status
            $approval->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
            
            // Notify the requester
            Notification::create([
                'user_id' => $approval->requested_by,
                'title' => 'Duplicate Lead Approved',
                'message' => "Your duplicate lead request for {$lead->name} ({$lead->email}) has been approved by {$user->name}.",
                'type' => 'approval',
                'data' => [
                    'approval_id' => $approval->id,
                    'lead_id' => $lead->id,
                    'lead_name' => $lead->name,
                ]
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.duplicate-lead-approvals.index')
                ->with('success', 'Duplicate lead request approved successfully! The lead has been created.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to approve duplicate lead', [
                'approval_id' => $approval->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to approve duplicate lead: ' . $e->getMessage());
        }
    }

    /**
     * Reject a duplicate lead request
     */
    public function reject(Request $request, $id)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can reject
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to reject duplicate lead requests.');
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection.',
            'rejection_reason.min' => 'Reason must be at least 10 characters long.',
        ]);
        
        $approval = DuplicateLeadApproval::findOrFail($id);
        
        if ($approval->status !== 'pending') {
            return redirect()->back()->with('info', 'This approval request has already been processed.');
        }
        
        try {
            // Update approval status
            $approval->update([
                'status' => 'rejected',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);
            
            // Notify the requester
            Notification::create([
                'user_id' => $approval->requested_by,
                'title' => 'Duplicate Lead Rejected',
                'message' => "Your duplicate lead request has been rejected by {$user->name}. Reason: {$request->rejection_reason}",
                'type' => 'approval',
                'data' => [
                    'approval_id' => $approval->id,
                    'rejection_reason' => $request->rejection_reason,
                ]
            ]);
            
            return redirect()->route('admin.duplicate-lead-approvals.index')
                ->with('success', 'Duplicate lead request rejected successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Failed to reject duplicate lead', [
                'approval_id' => $approval->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to reject duplicate lead: ' . $e->getMessage());
        }
    }
}
