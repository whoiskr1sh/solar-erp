<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Costing extends Model
{
    use HasFactory;

    protected $fillable = [
        'costing_number',
        'project_name',
        'project_id',
        'client_name',
        'client_email',
        'client_phone',
        'project_description',
        'location',
        'total_cost',
        'material_cost',
        'labor_cost',
        'equipment_cost',
        'transportation_cost',
        'overhead_cost',
        'profit_margin',
        'tax_rate',
        'discount',
        'currency',
        'status',
        'validity_date',
        'notes',
        'cost_breakdown',
        'created_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'total_cost' => 'decimal:2',
        'material_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'equipment_cost' => 'decimal:2',
        'transportation_cost' => 'decimal:2',
        'overhead_cost' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'discount' => 'decimal:2',
        'validity_date' => 'date',
        'cost_breakdown' => 'array',
        'approved_at' => 'datetime',
    ];

    // Relationships
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

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getFormattedTotalCostAttribute()
    {
        return '₹ ' . number_format($this->total_cost, 2);
    }

    public function getFormattedMaterialCostAttribute()
    {
        return '₹ ' . number_format($this->material_cost, 2);
    }

    public function getFormattedLaborCostAttribute()
    {
        return '₹ ' . number_format($this->labor_cost, 2);
    }

    public function getFormattedEquipmentCostAttribute()
    {
        return '₹ ' . number_format($this->equipment_cost, 2);
    }

    public function getFormattedTransportationCostAttribute()
    {
        return '₹ ' . number_format($this->transportation_cost, 2);
    }

    public function getFormattedOverheadCostAttribute()
    {
        return '₹ ' . number_format($this->overhead_cost, 2);
    }

    public function getFormattedDiscountAttribute()
    {
        return '₹ ' . number_format($this->discount, 2);
    }

    public function getIsExpiredAttribute()
    {
        return $this->validity_date && $this->validity_date < now();
    }

    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->validity_date) return null;
        return now()->diffInDays($this->validity_date, false);
    }

    // Methods
    public function calculateTotalCost()
    {
        $subtotal = $this->material_cost + $this->labor_cost + $this->equipment_cost + 
                   $this->transportation_cost + $this->overhead_cost;
        
        $profit = $subtotal * ($this->profit_margin / 100);
        $taxableAmount = $subtotal + $profit - $this->discount;
        $tax = $taxableAmount * ($this->tax_rate / 100);
        
        return $taxableAmount + $tax;
    }

    public function approve($userId)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now()
        ]);
    }

    public function reject($userId)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $userId,
            'approved_at' => now()
        ]);
    }

    public static function generateCostingNumber()
    {
        $year = now()->year;
        $month = now()->format('m');
        $count = static::whereYear('created_at', $year)
                      ->whereMonth('created_at', $month)
                      ->count() + 1;
        
        return "COST-{$year}{$month}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}