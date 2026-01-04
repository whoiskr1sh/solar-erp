<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_code',
        'warehouse_name',
        'location',
        'manager_name',
        'manager_email',
        'manager_phone',
        'status',
        'capacity_percentage',
        'total_items',
        'description',
    ];

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'maintenance' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Accessor for capacity color
    public function getCapacityColorAttribute()
    {
        return match(true) {
            $this->capacity_percentage >= 80 => 'bg-red-600',
            $this->capacity_percentage >= 60 => 'bg-yellow-600',
            default => 'bg-green-600'
        };
    }
}