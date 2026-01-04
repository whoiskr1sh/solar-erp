<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Escalation extends Model
{
    use HasFactory;

    protected $fillable = [
        'escalation_number',
        'title',
        'description',
        'type',
        'priority',
        'status',
        'category',
        'related_lead_id',
        'related_project_id',
        'related_invoice_id',
        'related_quotation_id',
        'related_commission_id',
        'assigned_to',
        'escalated_to',
        'created_by',
        'customer_name',
        'customer_email',
        'customer_phone',
        'due_date',
        'resolved_at',
        'closed_at',
        'resolution_notes',
        'internal_notes',
        'attachments',
        'tags',
        'escalation_level',
        'is_urgent',
        'requires_response',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'attachments' => 'array',
        'tags' => 'array',
        'is_urgent' => 'boolean',
        'requires_response' => 'boolean',
    ];

    // Relationships
    public function relatedLead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'related_lead_id');
    }

    public function relatedProject(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'related_project_id');
    }

    public function relatedInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'related_invoice_id');
    }

    public function relatedQuotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'related_quotation_id');
    }

    public function relatedCommission(): BelongsTo
    {
        return $this->belongsTo(Commission::class, 'related_commission_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function escalatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalated_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'critical']);
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->whereNotIn('status', ['resolved', 'closed']);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Helper methods
    public static function generateEscalationNumber(): string
    {
        $prefix = 'ESC';
        $year = date('Y');
        $month = date('m');
        
        $lastEscalation = self::where('escalation_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('escalation_number', 'desc')
            ->first();
        
        if ($lastEscalation) {
            $lastSequence = intval(substr($lastEscalation->escalation_number, -4));
            $sequence = $lastSequence + 1;
        } else {
            $sequence = 1;
        }
        
        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'open' => 'bg-red-100 text-red-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'resolved' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-gray-100 text-gray-600',
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
            'complaint' => 'bg-red-100 text-red-800',
            'issue' => 'bg-orange-100 text-orange-800',
            'request' => 'bg-blue-100 text-blue-800',
            'incident' => 'bg-purple-100 text-purple-800',
            'other' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getCategoryBadgeAttribute(): string
    {
        return match($this->category) {
            'technical' => 'bg-blue-100 text-blue-800',
            'billing' => 'bg-green-100 text-green-800',
            'service' => 'bg-purple-100 text-purple-800',
            'support' => 'bg-yellow-100 text-yellow-800',
            'general' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !in_array($this->status, ['resolved', 'closed']);
    }

    public function getIsDueSoonAttribute(): bool
    {
        return $this->due_date && $this->due_date->isFuture() && $this->due_date->diffInHours(now()) <= 24;
    }

    public function getDaysOpenAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    public function getFormattedDueDateAttribute(): string
    {
        return $this->due_date ? $this->due_date->format('M d, Y H:i') : 'Not set';
    }

    public function getFormattedResolvedAtAttribute(): string
    {
        return $this->resolved_at ? $this->resolved_at->format('M d, Y H:i') : 'Not resolved';
    }

    public function getFormattedClosedAtAttribute(): string
    {
        return $this->closed_at ? $this->closed_at->format('M d, Y H:i') : 'Not closed';
    }

    public function getRelatedEntityAttribute(): string
    {
        if ($this->relatedLead) return 'Lead: ' . $this->relatedLead->company;
        if ($this->relatedProject) return 'Project: ' . $this->relatedProject->name;
        if ($this->relatedInvoice) return 'Invoice: ' . $this->relatedInvoice->invoice_number;
        if ($this->relatedQuotation) return 'Quotation: ' . $this->relatedQuotation->quotation_number;
        if ($this->relatedCommission) return 'Commission: ' . $this->relatedCommission->commission_number;
        
        return 'No related entity';
    }

    // Status management methods
    public function markAsInProgress(): void
    {
        $this->update(['status' => 'in_progress']);
    }

    public function markAsResolved(string $resolutionNotes = null): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_notes' => $resolutionNotes,
        ]);
    }

    public function markAsClosed(): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    public function escalateTo(User $user): void
    {
        $this->update([
            'escalated_to' => $user->id,
            'escalation_level' => $this->escalation_level + 1,
            'is_urgent' => true,
        ]);
    }

    public function assignTo(User $user): void
    {
        $this->update(['assigned_to' => $user->id]);
    }
}
