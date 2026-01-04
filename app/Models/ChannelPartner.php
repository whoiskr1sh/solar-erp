<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChannelPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_code', 'company_name', 'contact_person', 'email', 'phone', 'alternate_phone',
        'address', 'city', 'state', 'pincode', 'country', 'gst_number', 'pan_number', 'website',
        'partner_type', 'status', 'commission_rate', 'credit_limit', 'outstanding_amount',
        'agreement_start_date', 'agreement_end_date', 'specializations', 'notes', 'bank_details',
        'documents', 'assigned_to', 'created_by'
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'outstanding_amount' => 'decimal:2',
        'agreement_start_date' => 'date',
        'agreement_end_date' => 'date',
        'specializations' => 'array',
        'bank_details' => 'array',
        'documents' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($partner) {
            if (empty($partner->partner_code)) {
                $partner->partner_code = $partner->generatePartnerCode();
            }
            if (empty($partner->created_by)) {
                $partner->created_by = auth()->id();
            }
        });
    }

    // Relationships
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'channel_partner_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'channel_partner_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('partner_type', $type);
    }

    public function scopeByAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'suspended' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPartnerTypeBadgeAttribute(): string
    {
        $badges = [
            'distributor' => 'bg-blue-100 text-blue-800',
            'dealer' => 'bg-purple-100 text-purple-800',
            'installer' => 'bg-green-100 text-green-800',
            'consultant' => 'bg-orange-100 text-orange-800',
            'other' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->partner_type] ?? 'bg-gray-100 text-gray-800';
    }

    public function getFormattedCommissionRateAttribute(): string
    {
        return $this->commission_rate . '%';
    }

    public function getFormattedCreditLimitAttribute(): string
    {
        return 'Rs. ' . number_format($this->credit_limit, 2);
    }

    public function getFormattedOutstandingAmountAttribute(): string
    {
        return 'Rs. ' . number_format($this->outstanding_amount, 2);
    }

    public function getIsAgreementExpiredAttribute(): bool
    {
        return $this->agreement_end_date && $this->agreement_end_date < now();
    }

    public function getIsAgreementExpiringSoonAttribute(): bool
    {
        return $this->agreement_end_date && $this->agreement_end_date->diffInDays(now()) <= 30;
    }

    public function getFullAddressAttribute(): string
    {
        $addressParts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->pincode,
            $this->country
        ]);

        return implode(', ', $addressParts);
    }

    // Helper methods
    public function generatePartnerCode(): string
    {
        $year = now()->year;
        $month = now()->format('m');
        $count = static::whereYear('created_at', $year)
                      ->whereMonth('created_at', $month)
                      ->count() + 1;
        
        return "CP-{$year}{$month}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    public function deactivate(): void
    {
        $this->update(['status' => 'inactive']);
    }

    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    public function updateOutstandingAmount($amount): void
    {
        $this->update(['outstanding_amount' => $amount]);
    }

    public function addSpecialization($specialization): void
    {
        $specializations = $this->specializations ?? [];
        if (!in_array($specialization, $specializations)) {
            $specializations[] = $specialization;
            $this->update(['specializations' => $specializations]);
        }
    }

    public function removeSpecialization($specialization): void
    {
        $specializations = $this->specializations ?? [];
        $specializations = array_filter($specializations, function($spec) use ($specialization) {
            return $spec !== $specialization;
        });
        $this->update(['specializations' => array_values($specializations)]);
    }
}