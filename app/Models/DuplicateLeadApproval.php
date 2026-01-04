<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuplicateLeadApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'requested_by',
        'existing_lead_id',
        'lead_data',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'lead_data' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who requested the duplicate lead
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the existing lead with the same email
     */
    public function existingLead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'existing_lead_id');
    }

    /**
     * Get the sales manager who approved/rejected
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if approval is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if approval is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if approval is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
