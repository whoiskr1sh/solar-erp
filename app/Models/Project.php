<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'project_code', 'status', 'priority',
        'start_date', 'end_date', 'budget', 'actual_cost', 
        'project_manager_id', 'project_engineer', 'liaisoning_officer', 'client_id', 'channel_partner_id', 'created_by', 
        'milestones', 'location'
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'milestones' => 'array',
    ];

    // Relationships
    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function projectEngineer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_engineer');
    }

    public function liaisoningOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'liaisoning_officer');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'client_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function channelPartner(): BelongsTo
    {
        return $this->belongsTo(ChannelPartner::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByManager($query, $managerId)
    {
        return $query->where('project_manager_id', $managerId);
    }

    public function scopeByEngineer($query, $engineerId)
    {
        return $query->where('project_engineer', $engineerId);
    }

    public function scopeByLiaisoningOfficer($query, $officerId)
    {
        return $query->where('liaisoning_officer', $officerId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'planning' => 'bg-blue-100 text-blue-800',
            'active' => 'bg-green-100 text-green-800',
            'on_hold' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->budget == 0) return 0;
        return round(($this->actual_cost / $this->budget) * 100, 2);
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->end_date) return null;
        return now()->diffInDays($this->end_date, false);
    }
}
