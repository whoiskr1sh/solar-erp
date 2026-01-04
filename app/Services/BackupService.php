<?php

namespace App\Services;

use App\Models\ModelBackup;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BackupService
{
    /**
     * Create a backup of any model before deletion
     * 
     * @param mixed $model The model instance to backup
     * @param int $deletedBy User ID who requested deletion
     * @param int $approvedBy User ID who approved deletion
     * @param string|null $reason Reason for deletion
     * @return int Backup ID
     */
    public function createBackup($model, $deletedBy, $approvedBy, $reason = null): int
    {
        try {
            $modelType = get_class($model);
            $modelData = $model->toArray();
            
            // Generate a display name for the model
            $modelName = $this->generateModelName($model, $modelType);
            
            $backup = ModelBackup::create([
                'model_type' => $modelType,
                'original_model_id' => $model->id,
                'model_name' => $modelName,
                'model_data' => $modelData,
                'deleted_by' => $deletedBy,
                'approved_by' => $approvedBy,
                'deletion_reason' => $reason,
                'deleted_at' => now(),
                'expires_at' => now()->addDays(40),
            ]);
            
            Log::info('Model backup created successfully', [
                'backup_id' => $backup->id,
                'model_type' => $modelType,
                'model_id' => $model->id,
                'model_name' => $modelName,
                'deleted_by' => $deletedBy,
                'approved_by' => $approvedBy,
            ]);
            
            return $backup->id;
        } catch (\Exception $e) {
            Log::error('Failed to create model backup', [
                'model_type' => get_class($model),
                'model_id' => $model->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Generate a human-readable name for the model
     */
    private function generateModelName($model, $modelType): string
    {
        $basename = class_basename($modelType);
        
        // Try to get a meaningful name based on model type
        switch ($basename) {
            case 'Lead':
                return 'Lead: ' . ($model->name ?? 'Unknown') . ($model->company ? ' (' . $model->company . ')' : '');
            
            case 'Quotation':
                $clientName = 'Unknown Client';
                try {
                    if (isset($model->client) && $model->client) {
                        $clientName = $model->client->name ?? 'Unknown Client';
                    } elseif (isset($model->client_id) && method_exists($model, 'client')) {
                        $client = $model->client;
                        $clientName = $client ? ($client->name ?? 'Unknown Client') : 'Unknown Client';
                    }
                } catch (\Exception $e) {
                    // Relationship not loaded or doesn't exist
                }
                return 'Quotation: #' . ($model->quotation_number ?? $model->id ?? 'N/A') . ' - ' . $clientName;
            
            case 'Project':
                $clientName = '';
                try {
                    if (isset($model->client) && $model->client) {
                        $clientName = ' - ' . ($model->client->name ?? '');
                    } elseif (isset($model->client_id) && method_exists($model, 'client')) {
                        $client = $model->client;
                        $clientName = $client ? (' - ' . ($client->name ?? '')) : '';
                    }
                } catch (\Exception $e) {
                    // Relationship not loaded or doesn't exist
                }
                return 'Project: ' . ($model->name ?? 'Unknown') . $clientName;
            
            case 'Customer':
                return 'Customer: ' . ($model->name ?? 'Unknown') . ($model->company ? ' (' . $model->company . ')' : '');
            
            case 'Invoice':
                $clientName = 'Unknown Client';
                try {
                    if (isset($model->client) && $model->client) {
                        $clientName = $model->client->name ?? 'Unknown Client';
                    } elseif (isset($model->client_id) && method_exists($model, 'client')) {
                        $client = $model->client;
                        $clientName = $client ? ($client->name ?? 'Unknown Client') : 'Unknown Client';
                    }
                } catch (\Exception $e) {
                    // Relationship not loaded or doesn't exist
                }
                return 'Invoice: #' . ($model->invoice_number ?? $model->id ?? 'N/A') . ' - ' . $clientName;
            
            case 'Vendor':
                return 'Vendor: ' . ($model->name ?? 'Unknown');
            
            case 'Task':
                $projectName = '';
                try {
                    if (isset($model->project) && $model->project) {
                        $projectName = ' - ' . ($model->project->name ?? '');
                    } elseif (isset($model->project_id) && method_exists($model, 'project')) {
                        $project = $model->project;
                        $projectName = $project ? (' - ' . ($project->name ?? '')) : '';
                    }
                } catch (\Exception $e) {
                    // Relationship not loaded or doesn't exist
                }
                return 'Task: ' . ($model->title ?? 'Unknown') . $projectName;
            
            case 'Document':
                return 'Document: ' . ($model->name ?? $model->file_name ?? 'Unknown');
            
            default:
                // Try common name fields
                if (isset($model->name)) {
                    return $basename . ': ' . $model->name;
                }
                if (isset($model->title)) {
                    return $basename . ': ' . $model->title;
                }
                if (isset($model->email)) {
                    return $basename . ': ' . $model->email;
                }
                return $basename . ' #' . ($model->id ?? 'N/A');
        }
    }
}

