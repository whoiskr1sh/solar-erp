<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contractor extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractor_code',
        'company_name',
        'contact_person',
        'designation',
        'email',
        'phone',
        'alternate_phone',
        'address',
        'city',
        'state',
        'pincode',
        'country',
        'contractor_type',
        'pan_number',
        'gst_number',
        'aadhar_number',
        'bank_name',
        'account_number',
        'ifsc_code',
        'branch_name',
        'specialization',
        'skills',
        'experience_description',
        'years_of_experience',
        'hourly_rate',
        'daily_rate',
        'monthly_rate',
        'currency',
        'availability',
        'availability_notes',
        'status',
        'status_notes',
        'rating',
        'total_projects',
        'total_value',
        'notes',
        'documents',
        'certifications',
        'created_by',
        'assigned_to',
        'is_verified',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'skills' => 'array',
        'documents' => 'array',
        'certifications' => 'array',
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'years_of_experience' => 'integer',
        'rating' => 'decimal:1',
        'total_projects' => 'integer',
        'total_value' => 'decimal:2',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($contractor) {
            if (empty($contractor->contractor_code)) {
                $contractor->contractor_code = 'CON-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            if (!$contractor->created_by) {
                $contractor->created_by = auth()->id() ?? 1;
            }
        });
    }

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Note: Projects and tasks relationships require respective tables to have contractor_id column
    // public function projects(): HasMany
    // {
    //     return $this->hasMany(Project::class);
    // }

    // public function tasks(): HasMany
    // {
    //     return $this->hasMany(Task::class);
    // }

    // Accessors
    public function getFormattedHourlyRateAttribute(): string
    {
        return $this->hourly_rate ? number_format($this->hourly_rate, 2) : 'N/A';
    }

    public function getFormattedDailyRateAttribute(): string
    {
        return $this->daily_rate ? number_format($this->daily_rate, 2) : 'N/A';
    }

    public function getFormattedMonthlyRateAttribute(): string
    {
        return $this->monthly_rate ? number_format($this->monthly_rate, 2) : 'N/A';
    }

    public function getFormattedTotalValueAttribute(): string
    {
        return number_format($this->total_value, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'suspended' => 'bg-yellow-100 text-yellow-800',
            'blacklisted' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getAvailabilityBadgeAttribute(): string
    {
        $badges = [
            'available' => 'bg-green-100 text-green-800',
            'busy' => 'bg-yellow-100 text-yellow-800',
            'unavailable' => 'bg-red-100 text-red-800',
            'on_project' => 'bg-blue-100 text-blue-800',
        ];

        return $badges[$this->availability] ?? 'bg-gray-100 text-gray-800';
    }

    public function getTypeBadgeAttribute(): string
    {
        $badges = [
            'individual' => 'bg-purple-100 text-purple-800',
            'company' => 'bg-blue-100 text-blue-800',
            'partnership' => 'bg-green-100 text-green-800',
            'subcontractor' => 'bg-orange-100 text-orange-800',
        ];

        return $badges[$this->contractor_type] ?? 'bg-gray-100 text-gray-800';
    }

    public function getRatingDisplayAttribute(): string
    {
        if (!$this->rating) return 'Not Rated';
        
        $stars = str_repeat('★', floor($this->rating));
        $emptyStars = str_repeat('☆', 5 - floor($this->rating));
        
        return $stars . $emptyStars . ' (' . $this->rating . '/5)';
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->pincode,
            $this->country
        ]);
        
        return implode(', ', $parts);
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->contractor_type === 'individual') {
            return $this->contact_person;
        }
        
        return $this->company_name . ' (' . $this->contact_person . ')';
    }

    public function getPrimaryContactAttribute(): string
    {
        return $this->contact_person;
    }

    // Methods
    public function markAsVerified(int $verifiedBy): void
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $verifiedBy,
        ]);
    }

    public function updateAvailability(string $availability, string $notes = null): void
    {
        $this->update([
            'availability' => $availability,
            'availability_notes' => $notes,
        ]);
    }

    public function incrementProjectStats(float $projectValue): void
    {
        $this->increment('total_projects');
        $this->increment('total_value', $projectValue);
    }

    public function updateRating(float $rating): void
    {
        $this->update(['rating' => $rating]);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('availability', 'available');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('contractor_type', $type);
    }

    public function scopeSpecializes($query, $specialization)
    {
        return $query->where('specialization', 'like', "%{$specialization}%");
    }

    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeInState($query, $state)
    {
        return $query->where('state', $state);
    }

    public function scopeWithRating($query, $minRating = 3.0)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeExperienced($query, $minYears = 2)
    {
        return $query->where('years_of_experience', '>=', $minYears);
    }
}