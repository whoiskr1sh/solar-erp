<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class MaterialConsumption extends Model
{
    protected $fillable = [
        'material_id',
        'material_request_id', 
        'project_id',
        'consumption_number',
        'activity_type',
        'activity_description',
        'work_phase',
        'work_location',
        'quantity_consumed',
        'unit_of_measurement',
        'consumption_percentage',
        'wastage_percentage',
        'return_percentage',
        'quality_status',
        'consumption_status',
        'waste_disposed',
        'return_to_stock',
        'unit_cost',
        'total_cost',
        'wastage_cost',
        'cost_center',
        'consumption_date',
        'start_time',
        'end_time',
        'duration_hours',
        'documentation_type',
        'documentation_path',
        'notes',
        'quality_observations',
        'consumed_by',
        'supervised_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'consumption_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'approved_at' => 'datetime',
        'consumption_percentage' => 'decimal:2',
        'wastage_percentage' => 'decimal:2', 
        'return_percentage' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'wastage_cost' => 'decimal:2',
        'waste_disposed' => 'boolean',
        'return_to_stock' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($consumption) {
            // Auto-generate consumption number
            if (empty($consumption->consumption_number)) {
                $consumption->consumption_number = 'CONS-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            // Set consumed by to current user if not specified
            if (!$consumption->consumed_by) {
                $consumption->consumed_by = Auth::id() ?? 1;
            }

            // Calculate total cost if unit cost is set
            if ($consumption->unit_cost && $consumption->quantity_consumed) {
                $consumption->total_cost = $consumption->unit_cost * $consumption->quantity_consumed;
            }

            // Calculate duration if start and end time are provided
            if ($consumption->start_time && $consumption->end_time) {
                $consumption->duration_hours = (new \DateTime($consumption->start_time))
                    ->diff(new \DateTime($consumption->end_time))->h;
            }
        });

        static::updating(function ($consumption) {
            // Recalculate total cost if unit cost or quantity changed
            if ($consumption->isDirty(['unit_cost', 'quantity_consumed']) && $consumption->unit_cost && $consumption->quantity_consumed) {
                $consumption->total_cost = $consumption->unit_cost * $consumption->quantity_consumed;
            }

            // Recalculate duration if start and end time changed
            if ($consumption->isDirty(['start_time', 'end_time']) && $consumption->start_time && $consumption->end_time) {
                $consumption->duration_hours = (new \DateTime($consumption->start_time))
                    ->diff(new \DateTime($consumption->end_time))->h;
            }
        });
    }

    // Relationships
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function materialRequest(): BelongsTo
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function consumedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consumed_by');
    }

    public function supervisedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervised_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getConsumptionStatusBadgeAttribute(): string
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'partial' => 'bg-yellow-100 text-yellow-800',
            'damaged' => 'bg-red-100 text-red-800',
            'returned' => 'bg-purple-100 text-purple-800',
        ];

        return $badges[$this->consumption_status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getQualityStatusBadgeAttribute(): string
    {
        $badges = [
            'good' => 'bg-green-100 text-green-800',
            'damaged' => 'bg-orange-100 text-orange-800',
            'defective' => 'bg-red-100 text-red-800',
            'expired' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->quality_status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getActivityTypeBadgeAttribute(): string
    {
        $badges = [
            'installation' => 'bg-blue-100 text-blue-800',
            'maintenance' => 'bg-green-100 text-green-800',
            'repair' => 'bg-yellow-100 text-yellow-800',
            'testing' => 'bg-purple-100 text-purple-800',
            'demo' => 'bg-indigo-100 text-indigo-800',
            'training' => 'bg-pink-100 text-pink-800',
        ];

        return $badges[$this->activity_type] ?? 'bg-gray-100 text-gray-800';
    }

    public function getWorkPhaseBadgeAttribute(): string
    {
        $badges = [
            'preparation' => 'bg-gray-100 text-gray-800',
            'foundation' => 'bg-orange-100 text-orange-800',
            'structure' => 'bg-blue-100 text-blue-800',
            'electrical' => 'bg-yellow-100 text-yellow-800',
            'commissioning' => 'bg-green-100 text-green-800',
            'maintenance' => 'bg-purple-100 text-purple-800',
            'other' => 'bg-indigo-100 text-indigo-800',
        ];

        return $badges[$this->work_phase] ?? 'bg-gray-100 text-gray-800';
    }

    public function getFormattedTotalCostAttribute(): string
    {
        return '₹' . number_format($this->total_cost, 2);
    }

    public function getFormattedUnitCostAttribute(): string
    {
        return '₹' . number_format($this->unit_cost, 2);
    }

    public function getFormattedWastageCostAttribute(): string
    {
        return '₹' . number_format($this->wastage_cost, 2);
    }

    public function getEfficiencyPercentageAttribute(): float
    {
        $total_percentage = $this->consumption_percentage + $this->wastage_percentage + $this->return_percentage;
        return $total_percentage > 0 ? round(($this->consumption_percentage / $total_percentage) * 100, 2) : 0;
    }

    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_hours) {
            return 'N/A';
        }

        $hours = floor($this->duration_hours);
        $minutes = ($this->duration_hours - $hours) * 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }

    // Scopes
    public function scopeFilter($query, array $filters)
    {
        // Filter by consumption date range
        if (isset($filters['date_from'])) {
            $query->where('consumption_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('consumption_date', '<=', $filters['date_to']);
        }

        // Filter by project
        if (isset($filters['project'])) {
            $query->where('project_id', $filters['project']);
        }

        // Filter by status
        if (isset($filters['status'])) {
            $query->where('consumption_status', $filters['status']);
        }

        // Filter by quality status
        if (isset($filters['quality_status'])) {
            $query->where('quality_status', $filters['quality_status']);
        }

        // Filter by work phase
        if (isset($filters['work_phase'])) {
            $query->where('work_phase', $filters['work_phase']);
        }

        // Filter by activity type
        if (isset($filters['activity_type'])) {
            $query->where('activity_type', $filters['activity_type']);
        }

        // Filter by consumed by user
        if (isset($filters['consumed_by'])) {
            $query->where('consumed_by', $filters['consumed_by']);
        }

        // Filter by high wastage (more than 10%)
        if (isset($filters['high_wastage'])) {
            $query->where('wastage_percentage', '>', 10);
        }

        // Filter by search term
        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('consumption_number', 'like', "%{$filters['search']}%")
                  ->orWhere('activity_description', 'like', "%{$filters['search']}%")
                  ->orWhereHas('material', function($subQuery) use ($filters) {
                      $subQuery->where('item_name', 'like', "%{$filters['search']}%");
                  });
            });
        }

        return $query;
    }

    // Business Logic Methods
    public function approve(): void
    {
        $this->update([
            'consumption_status' => 'completed',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Update material consumption quantity
        $this->material->update([
            'consumed_quantity' => $this->material->consumed_quantity + $this->quantity_consumed,
            'remaining_quantity' => max(0, $this->material->remaining_quantity - $this->quantity_consumed),
            'status' => $this->material->remaining_quantity <= 0 ? 'consumed' : 'available',
        ]);
    }

    public function recordWaste(): void
    {
        $this->update([
            'waste_disposed' => true,
            'consumption_status' => 'partial',
        ]);
    }

    public function returnToStock(int $returnQuantity): void
    {
        // Ensure quantity_consumed is not null or zero
        $quantityConsumed = $this->quantity_consumed ?? 1;
        
        $this->update([
            'return_percentage' => ($returnQuantity / $quantityConsumed) * 100,
            'return_to_stock' => true,
            'consumption_status' => 'returned',
        ]);

        // Update material stock
        $this->material->update([
            'remaining_quantity' => $this->material->remaining_quantity + $returnQuantity,
            'consumed_quantity' => max(0, $this->material->consumed_quantity - $returnQuantity),
            'status' => 'available',
        ]);
    }

    public function markCompleted(): void
    {
        $this->update(['consumption_status' => 'completed']);
    }

    public function isOverdue(): bool
    {
        return $this->consumption_date->isPast() && !$this->isCompleted();
    }

    public function isCompleted(): bool
    {
        return in_array($this->consumption_status, ['completed', 'returned']);
    }

    public function hasHighWastage(): bool
    {
        return $this->wastage_percentage > 10;
    }

    public function getTotalWasteQuantity(): int
    {
        return round(($this->quantity_consumed * $this->wastage_percentage) / 100);
    }

    public function getReturnQuantity(): int
    {
        return round(($this->quantity_consumed * $this->return_percentage) / 100);
    }
}