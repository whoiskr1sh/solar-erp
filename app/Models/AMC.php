<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AMC extends Model
{
    use HasFactory;

    protected $fillable = [
        'amc_number',
        'customer_name',
        'customer_email',
        'project_name',
        'project_location',
        'start_date',
        'end_date',
        'contract_value',
        'status',
        'services_included',
        'contact_person',
        'contact_phone',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'contract_value' => 'decimal:2',
    ];

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'expired' => 'bg-red-100 text-red-800',
            'renewed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Accessor for formatted contract value
    public function getFormattedContractValueAttribute()
    {
        return '₹' . number_format($this->contract_value, 2);
    }
}