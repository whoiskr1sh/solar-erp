<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ModelBackup extends Model
{
    use HasFactory;

    protected $table = 'model_backups';

    protected $fillable = [
        'model_type',
        'original_model_id',
        'model_name',
        'model_data',
        'deleted_by',
        'approved_by',
        'deletion_reason',
        'deleted_at',
        'expires_at',
        'is_restored',
        'restored_at',
        'restored_by',
    ];

    protected $casts = [
        'model_data' => 'array',
        'deleted_at' => 'datetime',
        'expires_at' => 'datetime',
        'restored_at' => 'datetime',
        'is_restored' => 'boolean',
    ];

    /**
     * Get the user who deleted the model
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the user who approved the deletion
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who restored the backup
     */
    public function restoredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    /**
     * Check if backup is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get days until expiration
     */
    public function daysUntilExpiration(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }
        
        if ($this->isExpired()) {
            return 0;
        }
        
        return now()->diffInDays($this->expires_at);
    }

    /**
     * Get the corrected model type (fixes namespace issues)
     */
    public function getCorrectedModelType(): string
    {
        $modelType = $this->model_type;
        
        // Fix common mistakes where controller namespace was used instead of model
        $modelType = str_replace('App\\Http\\Controllers\\', 'App\\Models\\', $modelType);
        
        return $modelType;
    }

    /**
     * Check if model class exists
     */
    public function modelClassExists(): bool
    {
        return class_exists($this->getCorrectedModelType());
    }

    /**
     * Get display name for the model type
     */
    public function getModelTypeDisplayAttribute(): string
    {
        $type = $this->getCorrectedModelType();
        $basename = class_basename($type);
        return $basename;
    }
}
