<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_code',
        'title',
        'description',
        'content',
        'category',
        'status',
        'priority',
        'effective_date',
        'review_date',
        'expiry_date',
        'version',
        'attachments',
        'approval_workflow',
        'created_by',
        'approved_by',
        'approved_at',
        'approval_notes',
        'is_mandatory',
        'requires_acknowledgment',
        'acknowledgment_instructions',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'review_date' => 'date',
        'expiry_date' => 'date',
        'approved_at' => 'datetime',
        'attachments' => 'array',
        'approval_workflow' => 'array',
        'is_mandatory' => 'boolean',
        'requires_acknowledgment' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($policy) {
            if (empty($policy->policy_code)) {
                $policy->policy_code = 'POL-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            if (!$policy->created_by) {
                $policy->created_by = auth()->id() ?? 1;
            }
        });
    }

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-yellow-100 text-yellow-800',
            'archived' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'low' => 'bg-blue-100 text-blue-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'critical' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getCategoryBadgeAttribute(): string
    {
        return match($this->category) {
            'hr_policies' => 'bg-purple-100 text-purple-800',
            'safety_policies' => 'bg-red-100 text-red-800',
            'it_policies' => 'bg-blue-100 text-blue-800',
            'financial_policies' => 'bg-green-100 text-green-800',
            'operational_policies' => 'bg-yellow-100 text-yellow-800',
            'quality_policies' => 'bg-indigo-100 text-indigo-800',
            'environmental_policies' => 'bg-teal-100 text-teal-800',
            'other' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeRequiresAcknowledgment($query)
    {
        return $query->where('requires_acknowledgment', true);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>', now());
    }

    public function scopeNeedsReview($query)
    {
        return $query->where('review_date', '<=', now())
                    ->where('status', 'active');
    }
}
