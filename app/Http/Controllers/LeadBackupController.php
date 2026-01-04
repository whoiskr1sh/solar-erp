<?php

namespace App\Http\Controllers;

use App\Models\LeadBackup;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadBackupController extends Controller
{
    /**
     * Display a listing of lead backups
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can view backups
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view lead backups.');
        }
        
        $query = LeadBackup::with(['deletedBy', 'approvedBy', 'creator', 'assignedUser'])
            ->orderBy('deleted_at', 'desc');
        
        // Filter by status (active/expired)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                });
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<=', now());
            }
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('deleted_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('deleted_at', '<=', $request->date_to);
        }
        
        $backups = $query->paginate(15);
        
        return view('admin.lead-backups.index', compact('backups'));
    }

    /**
     * Display the specified lead backup
     */
    public function show($id)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can view backups
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view lead backups.');
        }
        
        $backup = LeadBackup::with(['deletedBy', 'approvedBy', 'creator', 'assignedUser'])
            ->findOrFail($id);
        
        return view('admin.lead-backups.show', compact('backup'));
    }

    /**
     * Restore a deleted lead from backup
     */
    public function restore($id)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can restore backups
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to restore lead backups.');
        }
        
        $backup = LeadBackup::findOrFail($id);
        
        // Check if backup is expired
        if ($backup->isExpired()) {
            return redirect()->back()->with('error', 'Cannot restore an expired backup. The backup has exceeded the 40-day retention period.');
        }
        
        // Log restore attempt
        \Log::info('Restore attempt started', [
            'backup_id' => $backup->id,
            'backup_name' => $backup->name,
            'backup_email' => $backup->email,
            'backup_phone' => $backup->phone,
            'restored_by' => $user->id,
            'restored_by_name' => $user->name,
            'user_roles' => $user->roles->pluck('name')->toArray()
        ]);
        
        // Check if lead with same email already exists (if email exists)
        // If exists, we'll update it instead of creating a new one
        $existingLead = null;
        if ($backup->email) {
            $existingLead = Lead::where('email', $backup->email)->first();
        }
        
        // Also check by phone if email doesn't exist
        if (!$existingLead && $backup->phone) {
            $existingLead = Lead::where('phone', $backup->phone)->first();
        }
        
        // Create or update lead from backup
        try {
            \DB::beginTransaction();
            
            if ($existingLead) {
                // Update existing lead with backup data
                $updateData = [];
                
                // Only update fields that exist in the Lead model and have values
                if ($backup->name) $updateData['name'] = $backup->name;
                if ($backup->phone) $updateData['phone'] = $backup->phone;
                if ($backup->email !== null) $updateData['email'] = $backup->email;
                if ($backup->company !== null) $updateData['company'] = $backup->company;
                if ($backup->address !== null) $updateData['address'] = $backup->address;
                if ($backup->source) $updateData['source'] = $backup->source;
                if ($backup->status) $updateData['status'] = $backup->status;
                if ($backup->priority) $updateData['priority'] = $backup->priority;
                if ($backup->notes !== null) $updateData['notes'] = $backup->notes;
                if ($backup->estimated_value !== null) $updateData['estimated_value'] = $backup->estimated_value;
                if ($backup->expected_close_date !== null) $updateData['expected_close_date'] = $backup->expected_close_date;
                if ($backup->assigned_user_id !== null) $updateData['assigned_user_id'] = $backup->assigned_user_id;
                if ($backup->channel_partner_id !== null) $updateData['channel_partner_id'] = $backup->channel_partner_id;
                
                $existingLead->update($updateData);
                $lead = $existingLead;
                $message = 'Lead has been successfully restored and updated from backup!';
            } else {
                // Create new lead from backup
                // Ensure required fields are present
                if (empty($backup->name)) {
                    throw new \Exception('Cannot restore lead: name is required but missing in backup.');
                }
                if (empty($backup->phone)) {
                    throw new \Exception('Cannot restore lead: phone is required but missing in backup.');
                }
                
                $leadData = [
                    'name' => $backup->name,
                    'phone' => $backup->phone,
                    'source' => $backup->source ?? 'other',
                    'status' => $backup->status ?? 'new',
                    'priority' => $backup->priority ?? 'medium',
                    'created_by' => $backup->created_by ?? auth()->id(),
                ];
                
                // Add optional fields only if they exist
                if ($backup->email) {
                    $leadData['email'] = $backup->email;
                }
                if ($backup->company !== null) {
                    $leadData['company'] = $backup->company;
                }
                if ($backup->address !== null) {
                    $leadData['address'] = $backup->address;
                }
                if ($backup->notes !== null) {
                    $leadData['notes'] = $backup->notes;
                }
                if ($backup->estimated_value !== null) {
                    $leadData['estimated_value'] = $backup->estimated_value;
                }
                if ($backup->expected_close_date !== null) {
                    $leadData['expected_close_date'] = $backup->expected_close_date;
                }
                if ($backup->assigned_user_id !== null) {
                    $leadData['assigned_user_id'] = $backup->assigned_user_id;
                }
                if ($backup->channel_partner_id !== null) {
                    $leadData['channel_partner_id'] = $backup->channel_partner_id;
                }
                
                $lead = Lead::create($leadData);
                $message = 'Lead has been successfully restored from backup!';
                
                \Log::info('New lead created from backup', [
                    'backup_id' => $backup->id,
                    'lead_id' => $lead->id,
                    'lead_name' => $lead->name,
                    'lead_phone' => $lead->phone,
                    'lead_data' => $leadData
                ]);
            }
            
            // Verify the lead was created/updated successfully
            if (!$lead || !$lead->id) {
                throw new \Exception('Lead was not created/updated successfully. Lead ID is missing.');
            }
            
            // Refresh the lead to ensure it's loaded from database
            $lead->refresh();
            
            // Double-check the lead exists in database
            $verifyLead = Lead::find($lead->id);
            if (!$verifyLead) {
                throw new \Exception('Lead was created but cannot be found in database. Restore failed.');
            }
            
            // Only delete the backup after we've confirmed the lead exists
            $backupId = $backup->id;
            $backup->delete();
            
            \DB::commit();
            
            \Log::info('Lead restored from backup successfully', [
                'backup_id' => $backupId,
                'lead_id' => $lead->id,
                'lead_name' => $lead->name,
                'lead_email' => $lead->email,
                'lead_phone' => $lead->phone,
                'was_existing' => $existingLead !== null,
                'restored_by' => auth()->user()->id,
                'restored_by_name' => auth()->user()->name
            ]);
            
            // Redirect to leads index instead of show to avoid loading quotations immediately
            return redirect()->route('leads.index')
                ->with('success', $message . ' You can view the lead details from the leads list.');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            
            \Log::error('Failed to restore lead from backup', [
                'backup_id' => $backup->id ?? 'unknown',
                'backup_name' => $backup->name ?? 'unknown',
                'backup_email' => $backup->email ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'restored_by' => $user->id,
                'restored_by_name' => $user->name
            ]);
            
            $errorMessage = 'Failed to restore lead from backup. ';
            if (str_contains($e->getMessage(), 'SQLSTATE')) {
                $errorMessage .= 'Database error occurred. Please check the logs for details.';
            } else {
                $errorMessage .= $e->getMessage();
            }
            
            return redirect()->back()
                ->with('error', $errorMessage);
        }
    }
}

