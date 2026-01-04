<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteWarehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'warehouse_name',
        'location',
        'address',
        'contact_person',
        'contact_phone',
        'contact_email',
        'total_capacity',
        'used_capacity',
        'status',
        'description',
        'facilities',
        'managed_by',
    ];

    protected $casts = [
        'facilities' => 'array',
        'total_capacity' => 'decimal:2',
        'used_capacity' => 'decimal:2',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'maintenance' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getCapacityPercentageAttribute(): float
    {
        if ($this->total_capacity <= 0) return 0;
        return round(($this->used_capacity / $this->total_capacity) * 100, 2);
    }

    public function getAvailableCapacityAttribute(): float
    {
        return $this->total_capacity - $this->used_capacity;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByManager($query, $managerId)
    {
        return $query->where('managed_by', $managerId);
    }
}