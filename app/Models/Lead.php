<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'consumer_number', 'company', 'address', 'city', 'state', 'pincode', 'source', 
        'status', 'lead_stage', 'priority', 'notes', 'estimated_value', 
        'expected_close_date', 'follow_up_date', 'follow_up_notes', 'last_follow_up_at',
        'electricity_bill_path', 'cancelled_cheque_path',
        'aadhar_path', 'pan_path', 'other_document_name', 'other_document_path', 'passport_photo_path',
        'site_photo_pre_installation_path', 'site_photo_post_installation_path',
        'call_count', 'assigned_user_id', 'channel_partner_id', 'created_by', 'last_updated_by', 'is_reassigned',
        'selected_revised_quotation_id'
    ];

    public function selectedRevisedQuotation()
    {
        return $this->belongsTo(\App\Models\Quotation::class, 'selected_revised_quotation_id');
    }

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'expected_close_date' => 'date',
        'follow_up_date' => 'date',
        'last_follow_up_at' => 'datetime',
    ];

    // Relationships
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lastUpdater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public function channelPartner(): BelongsTo
    {
        return $this->belongsTo(ChannelPartner::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'client_id');
    }

    public function contactViews(): HasMany
    {
        return $this->hasMany(LeadContactView::class);
    }

    public function calls(): HasMany
    {
        return $this->hasMany(LeadCall::class);
    }

    /**
     * Get the user who should receive incentives/commissions for this lead
     * This is always the assigned_user_id, NOT the caller
     * 
     * @return User|null
     */
    public function getIncentiveRecipientAttribute()
    {
        return $this->assignedUser;
    }

    /**
     * Get the user ID who should receive incentives/commissions
     * Always returns assigned_user_id to ensure Person B gets credit, not Person A (caller)
     * 
     * @return int|null
     */
    public function getIncentiveRecipientIdAttribute()
    {
        return $this->assigned_user_id;
    }

    public function latestQuotations()
    {
        return $this->hasMany(Quotation::class, 'client_id')
            ->where(function($q) {
                $q->where('is_latest', true)
                  ->orWhereNull('parent_quotation_id');
            })
            ->orderBy('created_at', 'desc');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_user_id', $userId);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'interested' => 'bg-green-100 text-green-800',
            'not_interested' => 'bg-red-100 text-red-800',
            'partially_interested' => 'bg-yellow-100 text-yellow-800',
            'not_reachable' => 'bg-orange-100 text-orange-800',
            'not_answered' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Check if lead needs follow-up
     */
    public function needsFollowUp(): bool
    {
        // Statuses that always need follow-up
        if (in_array($this->status, ['partially_interested', 'not_reachable', 'not_answered'])) {
            return true;
        }
        
        // INTERESTED and PARTIALLY INTERESTED with overdue follow-up dates
        if (in_array($this->status, ['interested', 'partially_interested'])) {
            if ($this->follow_up_date && \Carbon\Carbon::parse($this->follow_up_date)->lt(now())) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if follow-up is overdue
     */
    public function isFollowUpOverdue(): bool
    {
        return $this->follow_up_date && \Carbon\Carbon::parse($this->follow_up_date)->lt(now());
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'interested' => 'Interested',
            'not_interested' => 'Not Interested',
            'partially_interested' => 'Partially Interested',
            'not_reachable' => 'Not Reachable',
            'not_answered' => 'Not Answered',
        ];

        return $labels[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'low' => 'bg-gray-100 text-gray-800',
            'medium' => 'bg-blue-100 text-blue-800',
            'high' => 'bg-orange-100 text-orange-800',
            'urgent' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->priority] ?? 'bg-gray-100 text-gray-800';
    }

    public function getLeadStageLabelAttribute(): string
    {
        $labels = [
            'quotation_sent' => 'Quotation Sent',
            'site_survey_done' => 'Site Survey Done',
            'solar_documents_collected' => 'Solar Documents Collected',
            'loan_documents_collected' => 'Loan Documents Collected',
        ];

        if (!$this->lead_stage) {
            return 'Not Set';
        }

        return $labels[$this->lead_stage] ?? ucfirst(str_replace('_', ' ', $this->lead_stage));
    }

    public function getLeadStageBadgeAttribute(): string
    {
        $badges = [
            'quotation_sent' => 'bg-yellow-100 text-yellow-800',
            'site_survey_done' => 'bg-orange-100 text-orange-800',
            'solar_documents_collected' => 'bg-green-100 text-green-800',
            'loan_documents_collected' => 'bg-green-100 text-green-800',
        ];

        if (!$this->lead_stage) {
            return 'bg-gray-100 text-gray-800';
        }

        return $badges[$this->lead_stage] ?? 'bg-gray-100 text-gray-800';
    }
}
