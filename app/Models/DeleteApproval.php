<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeleteApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'requested_by',
        'model_name',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'model_data',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'model_data' => 'array',
    ];

    /**
     * Get the user who requested the deletion
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the admin who approved/rejected
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the corrected model type (fixes namespace issues)
     */
    public function getCorrectedModelType()
    {
        $modelType = $this->model_type;
        
        // Fix common mistakes where controller namespace was used instead of model
        $modelType = str_replace('App\\Http\\Controllers\\', 'App\\Models\\', $modelType);
        
        return $modelType;
    }
    
    /**
     * Get the model instance
     */
    public function getModel()
    {
        // Fix incorrect class names that might be stored
        $modelType = $this->getCorrectedModelType();
        
        // Check if class exists
        if (!class_exists($modelType)) {
            \Log::error('DeleteApproval: Model class not found', [
                'model_type' => $this->model_type,
                'corrected_type' => $modelType,
                'model_id' => $this->model_id
            ]);
            return null;
        }
        
        return $modelType::find($this->model_id);
    }
}
