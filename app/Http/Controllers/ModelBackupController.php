<?php

namespace App\Http\Controllers;

use App\Models\ModelBackup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModelBackupController extends Controller
{
    /**
     * Display a listing of all backups
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can view backups
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view backups.');
        }
        
        $query = ModelBackup::with(['deletedBy', 'approvedBy', 'restoredBy'])
            ->orderBy('deleted_at', 'desc');
        
        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', 'like', '%' . $request->model_type . '%');
        }
        
        // Filter by status (active/expired/restored)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_restored', false)
                      ->where(function($q) {
                          $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                      });
            } elseif ($request->status === 'expired') {
                $query->where('is_restored', false)
                      ->where('expires_at', '<=', now());
            } elseif ($request->status === 'restored') {
                $query->where('is_restored', true);
            }
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('model_name', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%");
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
        
        // Get unique model types for filter
        $modelTypes = ModelBackup::distinct()
            ->whereNotNull('model_type')
            ->pluck('model_type')
            ->map(function($type) {
                // Fix incorrect namespaces
                $correctedType = str_replace('App\\Http\\Controllers\\', 'App\\Models\\', $type);
                return class_basename($correctedType);
            })
            ->unique()
            ->sort()
            ->values()
            ->filter(function($type) {
                return !empty($type);
            });
        
        return view('admin.model-backups.index', compact('backups', 'modelTypes'));
    }

    /**
     * Display the specified backup
     */
    public function show($id)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can view backups
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to view backups.');
        }
        
        $backup = ModelBackup::with(['deletedBy', 'approvedBy', 'restoredBy'])
            ->findOrFail($id);
        
        return view('admin.model-backups.show', compact('backup'));
    }

    /**
     * Restore a deleted model from backup
     */
    public function restore($id)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN and SALES MANAGER can restore backups
        if (!$user->hasRole('SUPER ADMIN') && !$user->hasRole('SALES MANAGER')) {
            abort(403, 'You do not have permission to restore backups.');
        }
        
        $backup = ModelBackup::findOrFail($id);
        
        // Check if backup is already restored
        if ($backup->is_restored) {
            return redirect()->back()->with('error', 'This backup has already been restored.');
        }
        
        // Check if backup is expired
        if ($backup->isExpired()) {
            return redirect()->back()->with('error', 'Cannot restore an expired backup. The backup has exceeded the 40-day retention period.');
        }
        
        // Check if model class exists
        if (!$backup->modelClassExists()) {
            return redirect()->back()->with('error', 'Cannot restore: Model class not found. The model may have been removed from the system.');
        }
        
        $modelType = $backup->getCorrectedModelType();
        
        // Validate that the model class exists and is instantiable
        if (!class_exists($modelType)) {
            return redirect()->back()->with('error', 'Cannot restore: Model class "' . $modelType . '" not found.');
        }
        
        try {
            DB::beginTransaction();
            
            // Check if model with same ID already exists
            $existingModel = $backup->original_model_id ? $modelType::find($backup->original_model_id) : null;
            
            if ($existingModel) {
                // Update existing model with backup data
                $modelData = $backup->model_data;
                
                // Remove timestamps, id, and relationships
                unset(
                    $modelData['id'], 
                    $modelData['created_at'], 
                    $modelData['updated_at'],
                    $modelData['assigned_user'],
                    $modelData['creator'],
                    $modelData['last_updater'],
                    $modelData['channel_partner'],
                    $modelData['projects'],
                    $modelData['invoices'],
                    $modelData['quotations']
                );
                
                // Filter out any fields that aren't in the model's fillable array
                $fillable = $existingModel->getFillable();
                $filteredData = array_intersect_key($modelData, array_flip($fillable));
                
                // Only update if there's valid data
                if (!empty($filteredData)) {
                    $existingModel->update($filteredData);
                }
                $model = $existingModel;
                $message = ucfirst($backup->getModelTypeDisplayAttribute()) . ' has been successfully restored and updated from backup!';
            } else {
                // Create new model from backup data
                $modelData = $backup->model_data;
                
                // Remove timestamps, id, and relationships to allow fresh creation
                unset(
                    $modelData['id'], 
                    $modelData['created_at'], 
                    $modelData['updated_at'],
                    $modelData['assigned_user'],
                    $modelData['creator'],
                    $modelData['last_updater'],
                    $modelData['channel_partner'],
                    $modelData['projects'],
                    $modelData['invoices'],
                    $modelData['quotations']
                );
                
                // Filter out any fields that aren't in the model's fillable array
                $modelInstance = new $modelType();
                $fillable = $modelInstance->getFillable();
                $filteredData = array_intersect_key($modelData, array_flip($fillable));
                
                // Ensure required fields are present
                if (empty($filteredData)) {
                    throw new \Exception('No valid data to restore. All fields were filtered out.');
                }
                
                $model = $modelType::create($filteredData);
                $message = ucfirst($backup->getModelTypeDisplayAttribute()) . ' has been successfully restored from backup!';
            }
            
            // Mark backup as restored
            $backup->update([
                'is_restored' => true,
                'restored_at' => now(),
                'restored_by' => $user->id,
            ]);
            
            DB::commit();
            
            Log::info('Model restored from backup successfully', [
                'backup_id' => $backup->id,
                'model_type' => $backup->model_type,
                'model_id' => $model->id,
                'restored_by' => $user->id,
            ]);
            
            // Determine redirect route based on model type
            $redirectRoute = $this->getRedirectRouteForModel($backup->model_type);
            
            return redirect()->route($redirectRoute)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to restore model from backup', [
                'backup_id' => $backup->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to restore from backup: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete a backup
     */
    public function destroy($id)
    {
        $user = auth()->user();
        
        // Only SUPER ADMIN can permanently delete backups
        if (!$user->hasRole('SUPER ADMIN')) {
            abort(403, 'You do not have permission to permanently delete backups.');
        }
        
        $backup = ModelBackup::findOrFail($id);
        
        try {
            $backup->delete();
            
            Log::info('Backup permanently deleted', [
                'backup_id' => $backup->id,
                'model_type' => $backup->model_type,
                'deleted_by' => $user->id,
            ]);
            
            return redirect()->route('admin.model-backups.index')
                ->with('success', 'Backup permanently deleted.');
                
        } catch (\Exception $e) {
            Log::error('Failed to delete backup', [
                'backup_id' => $backup->id,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to delete backup: ' . $e->getMessage());
        }
    }

    /**
     * Get redirect route based on model type
     */
    private function getRedirectRouteForModel($modelType): string
    {
        $basename = class_basename(str_replace('App\\Http\\Controllers\\', 'App\\Models\\', $modelType));
        
        $routeMap = [
            'Lead' => 'leads.index',
            'Quotation' => 'quotations.index',
            'Project' => 'projects.index',
            'Customer' => 'customers.index',
            'Invoice' => 'invoices.index',
            'Vendor' => 'vendors.index',
            'Task' => 'tasks.index',
            'Document' => 'documents.index',
        ];
        
        return $routeMap[$basename] ?? 'admin.model-backups.index';
    }
}
