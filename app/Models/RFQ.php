<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RFQ extends Model
{
    use HasFactory;

    protected $table = 'r_f_q_s';

    protected $fillable = [
        'rfq_number',
        'project_id',
        'vendor_id',
        'rfq_date',
        'quotation_due_date',
        'valid_until',
        'status',
        'description',
        'terms_conditions',
        'delivery_terms',
        'payment_terms',
        'estimated_budget',
        'created_by',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    protected $casts = [
        'rfq_date' => 'date',
        'quotation_due_date' => 'date',
        'valid_until' => 'date',
        'estimated_budget' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RFQItem::class, 'rfq_id');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'sent' => 'bg-blue-100 text-blue-800',
            'received' => 'bg-green-100 text-green-800',
            'evaluated' => 'bg-purple-100 text-purple-800',
            'awarded' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->quotation_due_date < now() && !in_array($this->status, ['awarded', 'cancelled']);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('quotation_due_date', '<', now())
                    ->whereNotIn('status', ['awarded', 'cancelled']);
    }
}