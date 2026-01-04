<?php

namespace App\Traits;

use App\Models\DeleteApproval;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\DeleteApprovalNotification;

trait HandlesDeletionApproval
{
    /**
     * Handle deletion with approval workflow
     * 
     * @param mixed $model The model instance to delete
     * @param string $modelName Display name for the model
     * @param string|null $reason Reason for deletion (optional)
     * @param string|null $redirectRoute Route to redirect after creation
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleDeletion($model, $modelName, $reason = null, $redirectRoute = null)
    {
        $user = auth()->user();
        
        // If user is SUPER ADMIN, delete directly (with backup for leads)
        if ($user->hasRole('SUPER ADMIN')) {
            // Create backup for leads
            if ($model instanceof \App\Models\Lead) {
                $this->createLeadBackup($model, $user->id, $user->id, $reason);
            }
            
            $model->delete();
            
            $route = $redirectRoute ?? $this->getDefaultRedirectRoute($model);
            return redirect()->route($route)->with('success', $modelName . ' deleted successfully!');
        }

        // For all other roles, create delete approval request
        if (!$reason) {
            return redirect()->back()->with('error', 'A reason is required for deletion.');
        }

        // Check if already has pending approval
        $existingApproval = DeleteApproval::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->where('status', 'pending')
            ->first();

        if ($existingApproval) {
            return redirect()->back()->with('info', 'A deletion request for this item is already pending approval from Admin.');
        }

        // Store model data before deletion
        $modelData = $model->toArray();

        $deleteApproval = DeleteApproval::create([
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'requested_by' => $user->id,
            'model_name' => $modelName,
            'reason' => $reason,
            'status' => 'pending',
            'model_data' => $modelData,
        ]);

        // Send email to SUPER ADMIN
        try {
            $admins = User::whereHas('roles', function($query) {
                $query->where('name', 'SUPER ADMIN');
            })->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new DeleteApprovalNotification($deleteApproval));
            }

            Log::info('Delete approval request created and email sent', [
                'delete_approval_id' => $deleteApproval->id,
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'requested_by' => $user->name,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send delete approval email', [
                'error' => $e->getMessage(),
                'delete_approval_id' => $deleteApproval->id,
            ]);
        }

        $route = $redirectRoute ?? $this->getDefaultRedirectRoute($model);
        return redirect()->route($route)->with('success', 'Deletion request sent to Admin for approval.');
    }

    /**
     * Get default redirect route based on model type
     */
    protected function getDefaultRedirectRoute($model)
    {
        $modelClass = get_class($model);
        $modelName = class_basename($modelClass);
        
        // Convert model name to route name (e.g., Lead -> leads.index)
        $routeName = strtolower(str_plural($modelName));
        
        // Handle special cases
        $routeMap = [
            'Lead' => 'leads.index',
            'Quotation' => 'quotations.index',
            'Project' => 'projects.index',
            'Task' => 'tasks.index',
            'Invoice' => 'invoices.index',
            'Product' => 'products.index',
            'Vendor' => 'vendors.index',
            'SiteExpense' => 'site-expenses.index',
            'Advance' => 'advances.index',
            'Expense' => 'expenses.index',
            'LeaveRequest' => 'hr.leave-management',
        ];
        
        return $routeMap[$modelName] ?? 'dashboard';
    }

    /**
     * Create backup of lead before deletion
     */
    protected function createLeadBackup(\App\Models\Lead $lead, $deletedBy, $approvedBy, $reason = null)
    {
        \DB::table('lead_backups')->insert([
            'original_lead_id' => $lead->id,
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'company' => $lead->company,
            'address' => $lead->address,
            'city' => $lead->city ?? null,
            'state' => $lead->state ?? null,
            'pincode' => $lead->pincode ?? null,
            'source' => $lead->source,
            'status' => $lead->status,
            'priority' => $lead->priority,
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
    }
}

