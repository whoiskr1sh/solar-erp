<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_number',
        'title',
        'description',
        'budget_category_id',
        'project_id',
        'budget_amount',
        'actual_amount',
        'currency',
        'budget_period',
        'start_date',
        'end_date',
        'status',
        'is_approved',
        'approved_by',
        'approved_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'budget_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'is_approved' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($budget) {
            if (empty($budget->budget_number)) {
                $budget->budget_number = 'BUD-' . str_pad(Budget::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(BudgetCategory::class, 'budget_category_id');
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

    public function getFormattedBudgetAmountAttribute()
    {
        return '$' . number_format($this->budget_amount, 2);
    }

    public function getFormattedActualAmountAttribute()
    {
        return '$' . number_format($this->actual_amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getVarianceAmountAttribute()
    {
        return $this->actual_amount - $this->budget_amount;
    }

    public function getVariancePercentageAttribute()
    {
        if ($this->budget_amount == 0) return 0;
        return round(($this->getVarianceAmountAttribute() / $this->budget_amount) * 100, 2);
    }

    public function getVarianceBadgeAttribute()
    {
        $variance = $this->getVarianceAmountAttribute();
        if ($variance > 0) {
            return 'bg-red-100 text-red-800'; // Over budget
        } elseif ($variance < 0) {
            return 'bg-green-100 text-green-800'; // Under budget
        } else {
            return 'bg-gray-100 text-gray-800'; // On budget
        }
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->budget_amount == 0) return 0;
        return round(($this->actual_amount / $this->budget_amount) * 100, 2);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('budget_category_id', $categoryId);
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeInPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate])
                   ->orWhereBetween('end_date', [$startDate, $endDate]);
    }
}
