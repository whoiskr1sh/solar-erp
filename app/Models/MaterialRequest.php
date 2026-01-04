<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'title',
        'description',
        'project_id',
        'priority',
        'status',
        'category',
        'request_type',
        'required_date',
        'approved_date',
        'completion_date',
        'total_amount',
        'approved_amount',
        'consumed_amount',
        'currency',
        'urgency_reason',
        'justification',
        'rejection_reason',
        'specifications',
        'attachments',
        'requested_by',
        'approved_by',
        'assigned_to',
        'notes',
        'is_urgent',
        'days_until_required',
    ];

    protected $casts = [
        'required_date' => 'date',
        'approved_date' => 'date',
        'completion_date' => 'date',
        'total_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'consumed_amount' => 'decimal:2',
        'specifications' => 'array',
        'attachments' => 'array',
        'is_urgent' => 'boolean',
        'days_until_required' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($request) {
            if (empty($request->request_number)) {
                $request->request_number = 'MR-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            if (!$request->requested_by) {
                $request->requested_by = auth()->id() ?? 1;
            }

            // Calculate days until required
            if ($request->required_date) {
                $request->days_until_required = now()->diffInDays($request->required_date, false);
            }
        });

        static::updating(function ($request) {
            // Update days until required
            if ($request->required_date) {
                $request->days_until_required = now()->diffInDays($request->required_date, false);
            }
        });
    }

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    // Accessors
    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 2);
    }

    public function getFormattedApprovedAmountAttribute(): string
    {
        return number_format($this->approved_amount, 2);
    }

    public function getFormattedConsumedAmountAttribute(): string
    {
        return number_format($this->consumed_amount, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'partial' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPriorityBadgeAttribute(): string
    {
        $badges = [
            'low' => 'bg-blue-100 text-blue-800',
            'medium' => 'bg-green-100 text-green-800',
            'high' => 'bg-yellow-100 text-yellow-800',
            'urgent' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->priority] ?? 'bg-gray-100 text-gray-800';
    }

    public function getCategoryBadgeAttribute(): string
    {
        $badges = [
            'raw_materials' => 'bg-blue-100 text-blue-800',
            'tools_equipment' => 'bg-purple-100 text-purple-800',
            'consumables' => 'bg-green-100 text-green-800',
            'safety_items' => 'bg-red-100 text-red-800',
            'electrical' => 'bg-yellow-100 text-yellow-800',
            'mechanical' => 'bg-gray-100 text-gray-800',
            'other' => 'bg-indigo-100 text-indigo-800',
        ];

        return $badges[$this->category] ?? 'bg-gray-100 text-gray-800';
    }

    public function getTypeBadgeAttribute(): string
    {
        $badges = [
            'purchase' => 'bg-green-100 text-green-800',
            'rental' => 'bg-blue-100 text-blue-800',
            'transfer' => 'bg-purple-100 text-purple-800',
            'emergency' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->request_type] ?? 'bg-gray-100 text-gray-800';
    }

    public function getDaysUntilRequiredAttribute(): int
    {
        if ($this->required_date) {
            return now()->diffInDays($this->required_date, false);
        }
        return 0;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->days_until_required < 0;
    }

    public function getIsUrgentAttribute(): bool
    {
        return $this->days_until_required <= 3 || $this->priority === 'urgent' || $this->is_urgent;
    }

    public function getCompletionPercentageAttribute(): float
    {
        if ($this->total_amount <= 0) return 0;
        
        return round(($this->consumed_amount / $this->total_amount) * 100, 2);
    }

    public function getApprovalPercentageAttribute(): float
    {
        if ($this->total_amount <= 0) return 0;
        
        return round(($this->approved_amount / $this->total_amount) * 100, 2);
    }

    // Methods
    public function approve(int $approvedBy, float $approvedAmount = null): void
    {
        $updateData = [
            'status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_date' => now(),
        ];

        if ($approvedAmount !== null) {
            $updateData['approved_amount'] = $approvedAmount;
        }

        $this->update($updateData);
    }

    public function reject(int $rejectedBy, string $rejectionReason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_by' => $rejectedBy,
            'rejection_reason' => $rejectionReason,
            'rejection_date' => now(),
        ]);
    }

    public function markInProgress(int $assignedTo = null): void
    {
        $updateData = ['status' => 'in_progress'];
        
        if ($assignedTo) {
            $updateData['assigned_to'] = $assignedTo;
        }

        $this->update($updateData);
    }

    public function markPartial(): void
    {
        $this->update(['status' => 'partial']);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completion_date' => now(),
        ]);
    }

    public function updateMaterialQuantities(): void
    {
        $totalAmount = $this->materials->sum('total_price');
        $consumedAmount = $this->materials->sum('consumed_quantity') * $this->materials->avg('unit_price');
        
        $this->update([
            'total_amount' => $totalAmount,
            'consumed_amount' => $consumedAmount,
        ]);
    }

    // Scopes
    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('request_number', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        })
        ->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        })
        ->when($filters['priority'] ?? null, function ($query, $priority) {
            $query->where('priority', $priority);
        })
        ->when($filters['category'] ?? null, function ($query, $category) {
            $query->where('category', $category);
        })
        ->when($filters['request_type'] ?? null, function ($query, $requestType) {
            $query->where('request_type', $requestType);
        })
        ->when($filters['project'] ?? null, function ($query, $project) {
            $query->where('project_id', $project);
        })
        ->when($filters['urgent'] ?? null, function ($query) {
            $query->where('priority', 'urgent')->orWhere('is_urgent', true);
        });
    }

    public function scopeUrgent($query)
    {
        return $query->where(function ($query) {
            $query->where('priority', 'urgent')
                  ->orWhere('is_urgent', true)
                  ->orWhere('days_until_required', '<=', 3);
        });
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'pending']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['completed', 'partial']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('required_date', '<', now());
    }
}