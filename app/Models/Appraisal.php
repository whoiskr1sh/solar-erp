<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'appraisal_type',
        'appraisal_date',
        'next_review_date',
        'performance_score',
        'strengths',
        'weaknesses',
        'development_plan',
        'manager_feedback',
        'status',
        'appraiser_name',
    ];

    protected $casts = [
        'appraisal_date' => 'date',
        'next_review_date' => 'date',
    ];

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'scheduled' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Accessor for performance score badge
    public function getPerformanceScoreBadgeAttribute()
    {
        return match(true) {
            $this->performance_score >= 4 => 'bg-green-100 text-green-800',
            $this->performance_score >= 3 => 'bg-yellow-100 text-yellow-800',
            $this->performance_score >= 2 => 'bg-orange-100 text-orange-800',
            default => 'bg-red-100 text-red-800'
        };
    }
}