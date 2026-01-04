<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'review_period',
        'review_date',
        'overall_rating',
        'goals_achieved',
        'areas_for_improvement',
        'manager_comments',
        'employee_comments',
        'status',
        'reviewed_by',
    ];

    protected $casts = [
        'review_date' => 'date',
    ];

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'completed' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Accessor for rating badge
    public function getRatingBadgeAttribute()
    {
        return match(true) {
            $this->overall_rating >= 4 => 'bg-green-100 text-green-800',
            $this->overall_rating >= 3 => 'bg-yellow-100 text-yellow-800',
            $this->overall_rating >= 2 => 'bg-orange-100 text-orange-800',
            default => 'bg-red-100 text-red-800'
        };
    }
}