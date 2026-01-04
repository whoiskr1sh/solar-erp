<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskAssignmentApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'requested_by',
        'assigned_to',
        'status',
        'manager_approved_by',
        'manager_approved_at',
        'manager_rejection_reason',
        'admin_approved_by',
        'admin_approved_at',
        'admin_rejection_reason',
    ];

    protected $casts = [
        'manager_approved_at' => 'datetime',
        'admin_approved_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function managerApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_approved_by');
    }

    public function adminApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_approved_by');
    }

    public function isPendingAtManagerLevel(): bool
    {
        return $this->status === 'pending_manager_approval';
    }

    public function isPendingAtAdminLevel(): bool
    {
        return $this->status === 'pending_admin_approval';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
