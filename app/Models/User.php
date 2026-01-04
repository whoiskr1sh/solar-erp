<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'department',
        'designation',
        'is_active',
        'is_available_for_followup',
        'unavailability_reason',
        'unavailable_until',
        'two_factor_enabled',
        'last_login_at',
        'employee_id',
        'joining_date',
        'salary',
        'address',
        'emergency_contact',
        'emergency_phone',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_available_for_followup' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'last_login_at' => 'datetime',
        'unavailable_until' => 'datetime',
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'settings' => 'array',
    ];

    /**
     * Get the leads created by this user.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'created_by');
    }

    /**
     * Get the tasks assigned to this user.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Get the projects managed by this user.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    /**
     * Get the invoices created by this user.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    /**
     * Get the quotations created by this user.
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'created_by');
    }

    /**
     * Get the documents created by this user.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'created_by');
    }

    /**
     * Get the vendors created by this user.
     */
    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class, 'created_by');
    }

    /**
     * Get the leads assigned to this user.
     */
    public function assignedLeads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_user_id');
    }

    /**
     * Check if user is currently available for follow-up
     */
    public function isAvailableForFollowup(): bool
    {
        if (!$this->is_available_for_followup) {
            return false;
        }

        // Check if unavailable_until date has passed
        if ($this->unavailable_until && $this->unavailable_until->isFuture()) {
            return false;
        }

        return true;
    }

    /**
     * Get availability status text
     */
    public function getAvailabilityStatusAttribute(): string
    {
        if ($this->isAvailableForFollowup()) {
            return 'Available';
        }

        if ($this->unavailable_until && $this->unavailable_until->isFuture()) {
            return 'Unavailable until ' . $this->unavailable_until->format('M d, Y');
        }

        return 'Unavailable';
    }
}
