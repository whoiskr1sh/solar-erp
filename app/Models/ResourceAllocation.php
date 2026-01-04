<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ResourceAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'allocation_code',
        'title',
        'description',
        'resource_type',
        'status',
        'priority',
        'project_id',
        'activity_id',
        'resource_name',
        'resource_category',
        'resource_specifications',
        'allocated_to',
        'allocated_by',
        'approved_by',
        'allocation_start_date',
        'allocation_end_date',
        'actual_start_date',
        'actual_end_date',
        'approved_at',
        'allocated_quantity',
        'actual_quantity',
        'quantity_unit',
        'hourly_rate',
        'unit_cost',
        'total_estimated_cost',
        'total_actual_cost',
        'budget_allocated',
        'utilization_percentage',
        'utilization_notes',
        'availability_schedule',
        'constraints',
        'dependencies',
        'notes',
        'attachments',
        'tags',
        'is_critical',
        'is_billable',
        'completion_notes',
    ];

    protected $casts = [
        'allocation_start_date' => 'datetime',
        'allocation_end_date' => 'datetime',
        'actual_start_date' => 'datetime',
        'actual_end_date' => 'datetime',
        'approved_at' => 'datetime',
        'allocated_quantity' => 'decimal:2',
        'actual_quantity' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_estimated_cost' => 'decimal:2',
        'total_actual_cost' => 'decimal:2',
        'budget_allocated' => 'decimal:2',
        'utilization_percentage' => 'decimal:2',
        'availability_schedule' => 'array',
        'constraints' => 'array',
        'dependencies' => 'array',
        'attachments' => 'array',
        'tags' => 'array',
        'is_critical' => 'boolean',
        'is_billable' => 'boolean',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function allocatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'allocated_to');
    }

    public function allocatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    public function scopeAllocated($query)
    {
        return $query->where('status', 'allocated');
    }

    public function scopeInUse($query)
    {
        return $query->where('status', 'in_use');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'critical']);
    }

    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    public function scopeByResourceType($query, $type)
    {
        return $query->where('resource_type', $type);
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    public function scopeAllocatedTo($query, $userId)
    {
        return $query->where('allocated_to', $userId);
    }

    public function scopeOverallocated($query)
    {
        return $query->whereRaw('actual_quantity > allocated_quantity');
    }

    public function scopeUnderutilized($query)
    {
        return $query->where('utilization_percentage', '<', 50);
    }

    // Helper methods
    public static function generateAllocationCode(): string
    {
        $prefix = 'RA';
        $year = date('Y');
        $month = date('m');
        
        $lastAllocation = self::where('allocation_code', 'like', $prefix . $year . $month . '%')
            ->orderBy('allocation_code', 'desc')
            ->first();
        
        if ($lastAllocation) {
            $lastSequence = intval(substr($lastAllocation->allocation_code, -4));
            $sequence = $lastSequence + 1;
        } else {
            $sequence = 1;
        }
        
        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'planned' => 'bg-blue-100 text-blue-800',
            'allocated' => 'bg-yellow-100 text-yellow-800',
            'in_use' => 'bg-green-100 text-green-800',
            'completed' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'low' => 'bg-green-100 text-green-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'critical' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getResourceTypeBadgeAttribute(): string
    {
        return match($this->resource_type) {
            'human' => 'bg-blue-100 text-blue-800',
            'equipment' => 'bg-purple-100 text-purple-800',
            'material' => 'bg-green-100 text-green-800',
            'financial' => 'bg-yellow-100 text-yellow-800',
            'other' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getIsOverallocatedAttribute(): bool
    {
        return $this->actual_quantity > $this->allocated_quantity;
    }

    public function getIsUnderutilizedAttribute(): bool
    {
        return $this->utilization_percentage < 50;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->allocation_end_date && 
               $this->allocation_end_date->isPast() && 
               !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) return 0;
        return $this->allocation_end_date->diffInDays(now());
    }

    public function getFormattedAllocationStartDateAttribute(): string
    {
        return $this->allocation_start_date ? $this->allocation_start_date->format('M d, Y H:i') : 'Not set';
    }

    public function getFormattedAllocationEndDateAttribute(): string
    {
        return $this->allocation_end_date ? $this->allocation_end_date->format('M d, Y H:i') : 'Not set';
    }

    public function getFormattedActualStartDateAttribute(): string
    {
        return $this->actual_start_date ? $this->actual_start_date->format('M d, Y H:i') : 'Not started';
    }

    public function getFormattedActualEndDateAttribute(): string
    {
        return $this->actual_end_date ? $this->actual_end_date->format('M d, Y H:i') : 'Not completed';
    }

    public function getFormattedHourlyRateAttribute(): string
    {
        return 'Rs. ' . number_format($this->hourly_rate, 2) . '/hour';
    }

    public function getFormattedUnitCostAttribute(): string
    {
        return 'Rs. ' . number_format($this->unit_cost, 2) . '/' . $this->quantity_unit;
    }

    public function getFormattedTotalEstimatedCostAttribute(): string
    {
        return 'Rs. ' . number_format($this->total_estimated_cost, 2);
    }

    public function getFormattedTotalActualCostAttribute(): string
    {
        return 'Rs. ' . number_format($this->total_actual_cost, 2);
    }

    public function getFormattedBudgetAllocatedAttribute(): string
    {
        return 'Rs. ' . number_format($this->budget_allocated, 2);
    }

    public function getUtilizationBarColorAttribute(): string
    {
        if ($this->utilization_percentage >= 100) return 'bg-red-500';
        if ($this->utilization_percentage >= 80) return 'bg-yellow-500';
        if ($this->utilization_percentage >= 50) return 'bg-green-500';
        return 'bg-blue-500';
    }

    // Status management methods
    public function markAsAllocated(): void
    {
        $this->update([
            'status' => 'allocated',
            'actual_start_date' => $this->actual_start_date ?: now(),
        ]);
    }

    public function markAsInUse(): void
    {
        $this->update([
            'status' => 'in_use',
            'actual_start_date' => $this->actual_start_date ?: now(),
        ]);
    }

    public function markAsCompleted(string $completionNotes = null): void
    {
        $this->update([
            'status' => 'completed',
            'actual_end_date' => now(),
            'completion_notes' => $completionNotes,
        ]);
    }

    public function markAsCancelled(string $notes = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $notes,
        ]);
    }

    public function approve(User $user): void
    {
        $this->update([
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);
    }

    public function updateUtilization(int $percentage, string $notes = null): void
    {
        $this->update([
            'utilization_percentage' => min(100, max(0, $percentage)),
            'utilization_notes' => $notes,
        ]);
    }

    public function addActualQuantity(float $quantity): void
    {
        $this->update([
            'actual_quantity' => $this->actual_quantity + $quantity,
        ]);
    }

    public function addActualCost(float $cost): void
    {
        $this->update([
            'total_actual_cost' => $this->total_actual_cost + $cost,
        ]);
    }

    public function calculateEstimatedCost(): float
    {
        if ($this->resource_type === 'human') {
            return $this->allocated_quantity * $this->hourly_rate;
        } else {
            return $this->allocated_quantity * $this->unit_cost;
        }
    }
}
