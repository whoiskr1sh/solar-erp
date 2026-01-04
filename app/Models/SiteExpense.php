<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_number', 'title', 'description', 'project_id', 'site_location',
        'expense_category', 'amount', 'expense_date', 'payment_method',
        'vendor_name', 'receipt_number', 'receipt_path', 'status', 'approval_level',
        'notes', 'created_by', 'approved_by', 'approved_at', 'rejection_reason',
        'hr_approved_by', 'hr_approved_at', 'hr_rejection_reason', 'admin_rejection_reason'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($expense) {
            if (empty($expense->expense_number)) {
                $count = static::whereYear('created_at', date('Y'))->count() + 1;
                $expense->expense_number = 'SE-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
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

    public function hrApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hr_approved_by');
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
            'paid' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
