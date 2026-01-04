<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OMMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_id',
        'project_name',
        'maintenance_type',
        'scheduled_date',
        'completed_date',
        'status',
        'technician_name',
        'description',
        'work_performed',
        'notes',
        'cost',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'cost' => 'decimal:2',
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

    // Accessor for formatted cost
    public function getFormattedCostAttribute()
    {
        return $this->cost ? '₹' . number_format($this->cost, 2) : 'N/A';
    }
}