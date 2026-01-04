<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyProgressReport extends Model
{
    use HasFactory;

    protected $table = 'daily_progress_reports';

    protected $fillable = [
        'project_id',
        'report_date',
        'weather_condition',
        'work_performed',
        'work_hours',
        'workers_present',
        'materials_used',
        'equipment_used',
        'challenges_faced',
        'next_day_plan',
        'photos',
        'status',
        'submitted_by',
        'approved_by',
        'approved_at',
        'remarks'
    ];

    protected $casts = [
        'report_date' => 'date',
        'photos' => 'array',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('report_date', $date);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getWeatherBadgeAttribute()
    {
        $badges = [
            'sunny' => 'bg-yellow-100 text-yellow-800',
            'cloudy' => 'bg-gray-100 text-gray-800',
            'rainy' => 'bg-blue-100 text-blue-800',
            'stormy' => 'bg-red-100 text-red-800',
            'foggy' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->weather_condition] ?? 'bg-gray-100 text-gray-800';
    }

    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    // Methods
    public function approve($userId, $remarks = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
            'remarks' => $remarks
        ]);
    }
}

