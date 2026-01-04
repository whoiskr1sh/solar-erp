<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProfitability extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'period',
        'start_date',
        'end_date',
        'total_revenue',
        'contract_value',
        'progress_billing',
        'overrun_revenue',
        'material_costs',
        'labor_costs',
        'equipment_costs',
        'transportation_costs',
        'permits_costs',
        'overhead_costs',
        'subcontractor_costs',
        'total_costs',
        'gross_profit',
        'gross_margin_percentage',
        'net_profit',
        'net_margin_percentage',
        'change_order_amount',
        'retention_amount',
        'days_completed',
        'total_days',
        'completion_percentage',
        'status',
        'notes',
        'created_by',
        'reviewed_by',
        'reviewed_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
        'total_revenue' => 'decimal:2',
        'contract_value' => 'decimal:2',
        'progress_billing' => 'decimal:2',
        'overrun_revenue' => 'decimal:2',
        'material_costs' => 'decimal:2',
        'labor_costs' => 'decimal:2',
        'equipment_costs' => 'decimal:2',
        'transportation_costs' => 'decimal:2',
        'permits_costs' => 'decimal:2',
        'overhead_costs' => 'decimal:2',
        'subcontractor_costs' => 'decimal:2',
        'total_costs' => 'decimal:2',
        'gross_profit' => 'decimal:2',
        'gross_margin_percentage' => 'decimal:2',
        'net_profit' => 'decimal:2',
        'net_margin_percentage' => 'decimal:2',
        'change_order_amount' => 'decimal:2',
        'retention_amount' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($profitability) {
            if (empty($profitability->created_by)) {
                $profitability->created_by = auth()->id();
            }
            
            // Auto-calculate totals
            $profitability->calculateTotals();
        });
        
        static::updating(function ($profitability) {
            $profitability->calculateTotals();
        });
    }

    public function calculateTotals()
    {
        // Calculate total costs
        $this->total_costs = $this->material_costs + $this->labor_costs + $this->equipment_costs + 
                           $this->transportation_costs + $this->permits_costs + $this->overhead_costs + 
                           $this->subcontractor_costs;

        // Calculate total revenue
        $this->total_revenue = $this->contract_value + $this->progress_billing + $this->overrun_revenue;

        // Calculate gross profit and margin
        $this->gross_profit = $this->total_revenue - $this->total_costs;
        $this->gross_margin_percentage = $this->total_revenue > 0 ? 
            ($this->gross_profit / $this->total_revenue) * 100 : 0;

        // For now, net profit = gross profit (can add taxes, etc later)
        $this->net_profit = $this->gross_profit;
        $this->net_margin_percentage = $this->total_revenue > 0 ? 
            ($this->net_profit / $this->total_revenue) * 100 : 0;

        // Calculate completion percentage if days provided
        if ($this->total_days > 0) {
            $this->completion_percentage = ($this->days_completed / $this->total_days) * 100;
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getFormattedGrossMarginAttribute()
    {

        return number_format($this->gross_margin_percentage, 1) . '%';
    }

    public function getFormattedNetMarginAttribute()
    {
        return number_format($this->net_margin_percentage, 1) . '%';
    }

    public function getFormattedGrossProfitAttribute()
    {
        return '$' . number_format($this->gross_profit, 2);
    }

    public function getFormattedNetProfitAttribute()
    {
        return '$' . number_format($this->net_profit, 2);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'reviewed' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'final' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
