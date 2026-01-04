<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class VendorRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'state',
        'pincode',
        'country',
        'gst_number',
        'pan_number',
        'registration_type',
        'registration_date',
        'business_description',
        'categories',
        'documents',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'reviewed_at' => 'datetime',
        'categories' => 'array',
        'documents' => 'array',
    ];

    // Relationships
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'under_review' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'suspended' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getRegistrationTypeBadgeAttribute(): string
    {
        return match($this->registration_type) {
            'Individual' => 'bg-blue-100 text-blue-800',
            'Partnership' => 'bg-green-100 text-green-800',
            'Company' => 'bg-purple-100 text-purple-800',
            'LLP' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getDaysSinceRegistrationAttribute(): int
    {
        return now()->diffInDays($this->registration_date);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByRegistrationType($query, $registrationType)
    {
        return $query->where('registration_type', $registrationType);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByState($query, $state)
    {
        return $query->where('state', $state);
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }
}