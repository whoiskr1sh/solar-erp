<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadBackup extends Model
{
    protected $table = 'lead_backups';

    protected $fillable = [
        'original_lead_id',
        'name',
        'email',
        'phone',
        'company',
        'address',
        'city',
        'state',
        'pincode',
        'source',
        'status',
        'priority',
        'notes',
        'estimated_value',
        'expected_close_date',
        'assigned_user_id',
        'channel_partner_id',
        'created_by',
        'deleted_by',
        'approved_by',
        'deleted_at',
        'expires_at',
        'deletion_reason',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'expected_close_date' => 'date',
        'deleted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user who deleted the lead
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the admin who approved the deletion
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who originally created the lead
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who was assigned to the lead
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
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
        
        return max(0, now()->diffInDays($this->expires_at, false));
    }
}

