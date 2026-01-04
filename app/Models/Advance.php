<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advance extends Model
{
    use HasFactory;

    protected $fillable = [
        'advance_number', 'title', 'description', 'employee_id', 'vendor_id',
        'project_id', 'advance_type', 'amount', 'advance_date',
        'expected_settlement_date', 'status', 'approval_level', 'settled_amount', 'purpose',
        'notes', 'created_by', 'approved_by', 'approved_at', 'rejection_reason',
        'manager_approved_by', 'manager_approved_at', 'manager_rejection_reason',
        'hr_approved_by', 'hr_approved_at', 'hr_rejection_reason',
        'admin_rejection_reason'
    ];

    protected $casts = [
        'advance_date' => 'date',
        'expected_settlement_date' => 'date',
        'approved_at' => 'datetime',
        'manager_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
        'amount' => 'decimal:2',
        'settled_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($advance) {
            if (empty($advance->advance_number)) {
                $count = static::whereYear('created_at', date('Y'))->count() + 1;
                $advance->advance_number = 'ADV-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function managerApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_approved_by');
    }

    public function hrApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hr_approved_by');
    }

    /**
     * Check if advance is pending at a specific approval level
     */
    public function isPendingAtLevel(string $level): bool
    {
        return $this->approval_level === $level && $this->status === 'pending';
    }

    /**
     * Get current approval level display name
     */
    public function getCurrentApprovalLevelAttribute(): string
    {
        return match($this->approval_level) {
            'hr' => 'HR Approval',
            'admin' => 'Admin Approval',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Pending',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'settled' => 'bg-blue-100 text-blue-800',
            'partially_settled' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->settled_amount;
    }
}
