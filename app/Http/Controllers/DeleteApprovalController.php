<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\DeleteApproval;
use App\Mail\DeleteApprovalNotification;
use App\Services\BackupService;

class DeleteApprovalController extends Controller
{
    /**
     * List all pending deletion requests
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can view deletion requests
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view deletion requests.');
        }
        
        $query = DeleteApproval::with(['requester', 'approver'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc');
        
        $deleteApprovals = $query->paginate(15);
        
        return view('admin.delete-approvals.index', compact('deleteApprovals'));
    }

    /**
     * Approve a delete request (Admin or Sales Manager for Leads)
     */
    public function approve($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $deleteApproval = DeleteApproval::findOrFail($id);
        
        // Check permissions based on model type
        $isLead = $deleteApproval->getCorrectedModelType() === \App\Models\Lead::class;
        
        // For leads, check if it's a "higher lead" (high/urgent priority or high estimated value)
        if ($isLead) {
            $model = $deleteApproval->getModel();
            $isHigherLead = false;
            
            if ($model) {
                $isHigherLead = in_array($model->priority ?? 'medium', ['high', 'urgent']) || 
                                ($model->estimated_value && $model->estimated_value >= 100000);
            }
            
            // For higher leads, only SUPER ADMIN can approve
            // For regular not_reachable leads, SALES MANAGER or SUPER ADMIN can approve
            if ($isHigherLead) {
                if (!$user->hasRole('SUPER ADMIN')) {
                    if (request()->get('from') === 'email') {
                        return view('admin.delete-approval-result', [
                            'success' => false,
                            'message' => 'You do not have permission to approve deletion of high-priority/high-value leads. Only Super Admin can approve.',
                            'deleteApproval' => $deleteApproval
                        ]);
                    }
                    return redirect()->back()->with('error', 'Only Super Admin can approve deletion of high-priority/high-value leads.');
                }
            } else {
                // Regular not_reachable leads can be approved by SALES MANAGER or SUPER ADMIN
                if (!$user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
                    if (request()->get('from') === 'email') {
                        return view('admin.delete-approval-result', [
                            'success' => false,
                            'message' => 'You do not have permission to approve lead deletions. Only Sales Manager or Super Admin can approve.',
                            'deleteApproval' => $deleteApproval
                        ]);
                    }
                    return redirect()->back()->with('error', 'Only Sales Manager or Super Admin can approve lead deletions.');
                }
            }
        } else {
            // For non-lead models, only SUPER ADMIN can approve
            if (!$user->hasRole('SUPER ADMIN')) {
                if (request()->get('from') === 'email') {
                    return view('admin.delete-approval-result', [
                        'success' => false,
                        'message' => 'You do not have permission to approve deletions. Only Super Admin can approve.',
                        'deleteApproval' => $deleteApproval
                    ]);
                }
                return redirect()->back()->with('error', 'Only Super Admin can approve deletions.');
            }
        }

        if ($deleteApproval->status !== 'pending') {
            if (request()->get('from') === 'email') {
                return view('admin.delete-approval-result', [
                    'success' => false,
                    'message' => 'This deletion request has already been processed.',
                    'deleteApproval' => $deleteApproval
                ]);
            }
            return redirect()->back()->with('info', 'This deletion request has already been processed.');
        }

        // Get the model instance
        $model = $deleteApproval->getModel();
        
        if (!$model) {
            $deleteApproval->update([
                'status' => 'rejected',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'rejection_reason' => 'Item not found or already deleted.'
            ]);
            
            if (request()->get('from') === 'email') {
                return view('admin.delete-approval-result', [
                    'success' => false,
                    'message' => 'Item not found or already deleted.',
                    'deleteApproval' => $deleteApproval
                ]);
            }
            return redirect()->back()->with('error', 'Item not found or already deleted.');
        }

        // Delete the model
        try {
            \DB::beginTransaction();
            
            $modelName = $deleteApproval->model_name;
            
            // Create backup for ALL models before deletion
            $backupService = new BackupService();
            $backupId = $backupService->createBackup(
                $model,
                $deleteApproval->requested_by,
                $user->id,
                $deleteApproval->reason
            );
            
            Log::info('Model backup created before deletion approval', [
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'backup_id' => $backupId,
                'approved_by' => $user->id
            ]);
            
            // Handle file deletions for models with file attachments
            if ($model instanceof \App\Models\SiteExpense && $model->receipt_path) {
                \Storage::disk('public')->delete($model->receipt_path);
            }
            if ($model instanceof \App\Models\Expense && $model->receipt_path) {
                \Storage::disk('public')->delete($model->receipt_path);
            }
            if ($model instanceof \App\Models\Document && $model->file_path) {
                \Storage::disk('public')->delete($model->file_path);
            }
            
            $model->delete();
            
            // Update approval status
            $deleteApproval->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);

            \DB::commit();

            Log::info('Delete approved and item deleted', [
                'delete_approval_id' => $deleteApproval->id,
                'model_type' => $deleteApproval->model_type,
                'model_id' => $deleteApproval->model_id,
                'approved_by' => $user->name,
            ]);

            if (request()->get('from') === 'email') {
                return view('admin.delete-approval-result', [
                    'success' => true,
                    'message' => $modelName . ' has been deleted successfully!',
                    'deleteApproval' => $deleteApproval
                ]);
            }

            return redirect()->route('admin.delete-approvals.index')->with('success', $modelName . ' has been deleted successfully!');
        } catch (\Exception $e) {
            \DB::rollBack();
            
            Log::error('Failed to delete item', [
                'error' => $e->getMessage(),
                'delete_approval_id' => $deleteApproval->id,
                'trace' => $e->getTraceAsString(),
            ]);

            if (request()->get('from') === 'email') {
                return view('admin.delete-approval-result', [
                    'success' => false,
                    'message' => 'Failed to delete item: ' . $e->getMessage(),
                    'deleteApproval' => $deleteApproval
                ]);
            }

            return redirect()->back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
    }

    /**
     * Reject a delete request (Admin or Sales Manager for Leads)
     */
    public function reject(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $deleteApproval = DeleteApproval::findOrFail($id);
        
        // Check permissions based on model type
        $isLead = $deleteApproval->getCorrectedModelType() === \App\Models\Lead::class;
        
        if ($isLead) {
            // For leads, SALES MANAGER or SUPER ADMIN can reject
            if (!$user->hasRole('SALES MANAGER') && !$user->hasRole('SUPER ADMIN')) {
                if (request()->get('from') === 'email') {
                    return view('admin.delete-approval-result', [
                        'success' => false,
                        'message' => 'You do not have permission to reject lead deletions. Only Sales Manager or Super Admin can reject.',
                        'deleteApproval' => $deleteApproval
                    ]);
                }
                return redirect()->back()->with('error', 'Only Sales Manager or Super Admin can reject lead deletions.');
            }
        } else {
            // For other models, only SUPER ADMIN can reject
            if (!$user->hasRole('SUPER ADMIN')) {
                if (request()->get('from') === 'email') {
                    return view('admin.delete-approval-result', [
                        'success' => false,
                        'message' => 'You do not have permission to reject deletions.',
                        'deleteApproval' => DeleteApproval::findOrFail($id)
                    ]);
                }
                return redirect()->back()->with('error', 'Only SUPER ADMIN can reject deletions.');
            }
        }

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);
        
        $deleteApproval->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        Log::info('Delete request rejected', [
            'delete_approval_id' => $deleteApproval->id,
            'rejected_by' => $user->name,
        ]);

        if (request()->get('from') === 'email') {
            return view('admin.delete-approval-result', [
                'success' => true,
                'message' => 'Deletion request rejected successfully.',
                'deleteApproval' => $deleteApproval
            ]);
        }

        return redirect()->route('admin.delete-approvals.index')->with('success', 'Deletion request rejected.');
    }

    /**
     * View lead details before approval/rejection
     */
    public function view($id)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can view deletion requests
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view deletion requests.');
        }
        
        $deleteApproval = DeleteApproval::findOrFail($id);
        
        // Only show for Lead deletions
        if ($deleteApproval->getCorrectedModelType() !== \App\Models\Lead::class) {
            return redirect()->route('admin.delete-approvals.index')
                ->with('error', 'This view is only available for lead deletion requests.');
        }
        
        // Get the lead
        $lead = $deleteApproval->getModel();
        
        if (!$lead) {
            return redirect()->route('admin.delete-approvals.index')
                ->with('error', 'Lead not found. It may have already been deleted.');
        }
        
        // Load relationships
        $lead->load(['assignedUser', 'creator', 'channelPartner']);
        // Load projects and invoices counts
        $lead->loadCount(['projects', 'invoices']);
        
        return view('admin.delete-approvals.view-lead', compact('deleteApproval', 'lead'));
    }

    /**
     * Show reject form (for email links)
     */
    public function showRejectForm($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $deleteApproval = DeleteApproval::findOrFail($id);
        
        // Only SUPER ADMIN can reject deletions
        if (!$user->hasRole('SUPER ADMIN')) {
            if (request()->get('from') === 'email') {
                return view('admin.delete-approval-result', [
                    'success' => false,
                    'message' => 'You do not have permission to reject deletions. Only Super Admin can reject.',
                    'deleteApproval' => $deleteApproval
                ]);
            }
            return redirect()->back()->with('error', 'Only Super Admin can reject deletions.');
        }
        
        return view('admin.reject-delete', compact('deleteApproval'));
    }

    /**
     * Create backup of lead before deletion
     */
    private function createLeadBackup(\App\Models\Lead $lead, $deletedBy, $approvedBy, $reason = null)
    {
        try {
            $backupId = \DB::table('lead_backups')->insertGetId([
                'original_lead_id' => $lead->id,
                'name' => $lead->name ?? '',
                'email' => $lead->email,
                'phone' => $lead->phone ?? '',
                'company' => $lead->company,
                'address' => $lead->address,
                'source' => $lead->source ?? 'other',
                'status' => $lead->status ?? 'interested',
                'priority' => $lead->priority ?? 'medium',
                'notes' => $lead->notes,
                'estimated_value' => $lead->estimated_value,
                'expected_close_date' => $lead->expected_close_date,
                'assigned_user_id' => $lead->assigned_user_id,
                'channel_partner_id' => $lead->channel_partner_id,
                'created_by' => $lead->created_by,
                'deleted_by' => $deletedBy,
                'approved_by' => $approvedBy,
                'deleted_at' => now(),
                'expires_at' => now()->addDays(40),
                'deletion_reason' => $reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            Log::info('Lead backup created successfully in DeleteApprovalController', [
                'lead_id' => $lead->id,
                'backup_id' => $backupId,
                'deleted_by' => $deletedBy,
                'approved_by' => $approvedBy
            ]);
            
            return $backupId;
        } catch (\Exception $e) {
            Log::error('Failed to create lead backup in DeleteApprovalController', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
