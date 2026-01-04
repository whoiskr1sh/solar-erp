<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_code',
        'title',
        'description',
        'type',
        'status',
        'priority',
        'project_id',
        'phase_id',
        'assigned_to',
        'created_by',
        'approved_by',
        'planned_start_date',
        'planned_end_date',
        'actual_start_date',
        'actual_end_date',
        'approved_at',
        'estimated_hours',
        'actual_hours',
        'progress_percentage',
        'progress_notes',
        'dependencies',
        'blockers',
        'resources',
        'deliverables',
        'acceptance_criteria',
        'completion_notes',
        'notes',
        'attachments',
        'tags',
        'is_milestone',
        'is_billable',
        'estimated_cost',
        'actual_cost',
    ];

    protected $casts = [
        'planned_start_date' => 'datetime',
        'planned_end_date' => 'datetime',
        'actual_start_date' => 'datetime',
        'actual_end_date' => 'datetime',
        'approved_at' => 'datetime',
        'dependencies' => 'array',
        'blockers' => 'array',
        'resources' => 'array',
        'attachments' => 'array',
        'tags' => 'array',
        'is_milestone' => 'boolean',
        'is_billable' => 'boolean',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }


    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
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
    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOnHold($query)
    {
        return $query->where('status', 'on_hold');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'critical']);
    }

    public function scopeMilestones($query)
    {
        return $query->where('is_milestone', true);
    }

    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('planned_end_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper methods
    public static function generateActivityCode(): string
    {
        $prefix = 'ACT';
        $year = date('Y');
        $month = date('m');
        
        $lastActivity = self::where('activity_code', 'like', $prefix . $year . $month . '%')
            ->orderBy('activity_code', 'desc')
            ->first();
        
        if ($lastActivity) {
            $lastSequence = intval(substr($lastActivity->activity_code, -4));
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
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'on_hold' => 'bg-orange-100 text-orange-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
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

    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'task' => 'bg-blue-100 text-blue-800',
            'milestone' => 'bg-purple-100 text-purple-800',
            'meeting' => 'bg-green-100 text-green-800',
            'delivery' => 'bg-orange-100 text-orange-800',
            'review' => 'bg-yellow-100 text-yellow-800',
            'other' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->planned_end_date && 
               $this->planned_end_date->isPast() && 
               !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getIsDueSoonAttribute(): bool
    {
        return $this->planned_end_date && 
               $this->planned_end_date->isFuture() && 
               $this->planned_end_date->diffInDays(now()) <= 3;
    }

    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) return 0;
        return $this->planned_end_date->diffInDays(now());
    }

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->planned_end_date || $this->planned_end_date->isPast()) return 0;
        return $this->planned_end_date->diffInDays(now());
    }

    public function getFormattedPlannedStartDateAttribute(): string
    {
        return $this->planned_start_date ? $this->planned_start_date->format('M d, Y H:i') : 'Not set';
    }

    public function getFormattedPlannedEndDateAttribute(): string
    {
        return $this->planned_end_date ? $this->planned_end_date->format('M d, Y H:i') : 'Not set';
    }

    public function getFormattedActualStartDateAttribute(): string
    {
        return $this->actual_start_date ? $this->actual_start_date->format('M d, Y H:i') : 'Not started';
    }

    public function getFormattedActualEndDateAttribute(): string
    {
        return $this->actual_end_date ? $this->actual_end_date->format('M d, Y H:i') : 'Not completed';
    }

    public function getFormattedEstimatedCostAttribute(): string
    {
        return 'Rs. ' . number_format($this->estimated_cost, 2);
    }

    public function getFormattedActualCostAttribute(): string
    {
        return 'Rs. ' . number_format($this->actual_cost, 2);
    }

    public function getProgressBarColorAttribute(): string
    {
        if ($this->progress_percentage >= 100) return 'bg-green-500';
        if ($this->progress_percentage >= 75) return 'bg-blue-500';
        if ($this->progress_percentage >= 50) return 'bg-yellow-500';
        if ($this->progress_percentage >= 25) return 'bg-orange-500';
        return 'bg-red-500';
    }

    // Status management methods
    public function markAsInProgress(): void
    {
        $this->update([
            'status' => 'in_progress',
            'actual_start_date' => $this->actual_start_date ?: now(),
        ]);
    }

    public function markAsCompleted(string $completionNotes = null): void
    {
        $this->update([
            'status' => 'completed',
            'actual_end_date' => now(),
            'progress_percentage' => 100,
            'completion_notes' => $completionNotes,
        ]);
    }

    public function markAsOnHold(string $notes = null): void
    {
        $this->update([
            'status' => 'on_hold',
            'notes' => $notes,
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

    public function updateProgress(int $percentage, string $notes = null): void
    {
        $this->update([
            'progress_percentage' => min(100, max(0, $percentage)),
            'progress_notes' => $notes,
        ]);
    }

    public function addActualHours(int $hours): void
    {
        $this->update([
            'actual_hours' => $this->actual_hours + $hours,
        ]);
    }

    public function addActualCost(float $cost): void
    {
        $this->update([
            'actual_cost' => $this->actual_cost + $cost,
        ]);
    }
}
