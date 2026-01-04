<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_number',
        'job_title',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'resume_path',
        'cover_letter',
        'status',
        'application_date',
        'interview_date',
        'interview_notes',
        'interviewer_name',
    ];

    protected $casts = [
        'application_date' => 'date',
        'interview_date' => 'date',
    ];

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'applied' => 'bg-blue-100 text-blue-800',
            'screening' => 'bg-yellow-100 text-yellow-800',
            'interview' => 'bg-orange-100 text-orange-800',
            'selected' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}