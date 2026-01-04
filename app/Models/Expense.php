<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_number',
        'title',
        'description',
        'expense_category_id',
        'project_id',
        'amount',
        'currency',
        'expense_date',
        'payment_method',
        'status',
        'approval_level',
        'notes',
        'receipt_path',
        'created_by',
        'approved_by',
        'approved_at',
        'manager_approved_by',
        'manager_approved_at',
        'manager_rejection_reason',
        'hr_approved_by',
        'hr_approved_at',
        'hr_rejection_reason',
        'admin_rejection_reason'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'approved_at' => 'datetime',
        'manager_approved_at' => 'datetime',
        'hr_approved_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($expense) {
            if (empty($expense->expense_number)) {
                $expense->expense_number = 'EXP' . date('Y') . str_pad(static::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function managerApprover()
    {
        return $this->belongsTo(User::class, 'manager_approved_by');
    }

    public function hrApprover()
    {
        return $this->belongsTo(User::class, 'hr_approved_by');
    }

    /**
     * Check if expense is pending at a specific approval level
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
            'manager' => 'Manager Approval',
            'hr' => 'HR Approval',
            'admin' => 'Admin Approval',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Pending',
        };
    }

    public function getFormattedAmountAttribute()
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'paid' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPaymentMethodBadgeAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'bg-green-100 text-green-800',
            'card' => 'bg-blue-100 text-blue-800',
            'transfer' => 'bg-purple-100 text-purple-800',
            'cheque' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
