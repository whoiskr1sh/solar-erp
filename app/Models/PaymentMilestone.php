<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMilestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone_number',
        'title',
        'description',
        'project_id',
        'quotation_id',
        'milestone_amount',
        'paid_amount',
        'currency',
        'milestone_type',
        'milestone_percentage',
        'planned_date',
        'due_date',
        'payment_date',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'payment_notes',
        'milestone_notes',
        'created_by',
        'assigned_to',
        'paid_by',
        'paid_at',
        'is_active',
        'attachments',
    ];

    protected $casts = [
        'milestone_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'planned_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'milestone_percentage' => 'integer',
        'is_active' => 'boolean',
        'paid_at' => 'datetime',
        'attachments' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($milestone) {
            if (empty($milestone->milestone_number)) {
                $milestone->milestone_number = 'MS-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            if (!$milestone->created_by) {
                $milestone->created_by = auth()->id() ?? 1;
            }
        });

        static::created(function ($milestone) {
            $milestone->updateMilestonePercentage();
        });

        static::updated(function ($milestone) {
            $milestone->updateMilestonePercentage();
        });
    }

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    // Accessors
    public function getFormattedMilestoneAmountAttribute(): string
    {
        return number_format($this->milestone_amount, 2);
    }

    public function getFormattedPaidAmountAttribute(): string
    {
        return number_format($this->paid_amount, 2);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->milestone_amount - $this->paid_amount);
    }

    public function getFormattedRemainingAmountAttribute(): string
    {
        return number_format($this->remaining_amount, 2);
    }

    public function getPaymentPercentageAttribute(): float
    {
        if ($this->milestone_amount == 0) return 0;
        return round(($this->paid_amount / $this->milestone_amount) * 100, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'paid' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPaymentStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'bg-yellow-200 text-yellow-800',
            'paid' => 'bg-green-200 text-green-800',
            'partial' => 'bg-blue-200 text-blue-800',
            'overdue' => 'bg-red-200 text-red-800',
            'cancelled' => 'bg-gray-200 text-gray-800',
        ];

        return $badges[$this->payment_status] ?? 'bg-gray-200 text-gray-800';
    }

    public function getTypeBadgeAttribute(): string
    {
        $badges = [
            'advance' => 'bg-purple-100 text-purple-800',
            'progress' => 'bg-blue-100 text-blue-800',
            'completion' => 'bg-green-100 text-green-800',
            'retention' => 'bg-yellow-100 text-yellow-800',
            'final' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->milestone_type] ?? 'bg-gray-100 text-gray-800';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'pending' && $this->due_date < now();
    }

    public function getDaysUntilDueAttribute(): int
    {
        return max(0, $this->due_date->diffInDays(now()));
    }

    // Methods
    public function markAsPaid(float $amount = null, string $paymentMethod = null, string $reference = null, string $notes = null)
    {
        $amount = $amount ?? $this->remaining_amount;
        
        $this->update([
            'paid_amount' => min($this->paid_amount + $amount, $this->milestone_amount),
            'payment_date' => now(),
            'payment_method' => $paymentMethod,
            'payment_reference' => $reference,
            'payment_notes' => $notes,
            'payment_status' => $this->paid_amount >= $this->milestone_amount ? 'paid' : 'partial',
            'status' => $this->paid_amount >= $this->milestone_amount ? 'paid' : 'completed',
            'paid_by' => auth()->id(),
            'paid_at' => now(),
        ]);
    }

    public function updateMilestonePercentage()
    {
        // Auto-update milestone percentage based on status
        switch ($this->status) {
            case 'pending':
                $this->milestone_percentage = 0;
                break;
            case 'in_progress':
                $this->milestone_percentage = min(90, $this->milestone_percentage ?: 50);
                break;
            case 'completed':
            case 'paid':
                $this->milestone_percentage = 100;
                break;
        }
    }

    public function getDelayDaysAttribute(): int
    {
        if ($this->status === 'paid' || $this->status === 'cancelled') {
            return 0;
        }
        
        return max(0, now()->diffInDays($this->due_date, false));
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                   ->where('due_date', '<', now());
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }
}