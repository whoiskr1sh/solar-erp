<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'claim_number',
        'expense_type',
        'expense_date',
        'amount',
        'description',
        'receipt_path',
        'status',
        'approved_by',
        'approved_date',
        'rejection_reason',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'approved_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'submitted' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'paid' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Accessor for formatted amount
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }
}