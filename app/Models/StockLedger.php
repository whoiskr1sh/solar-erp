<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'transaction_time',
        'item_name',
        'item_code',
        'transaction_type',
        'reference_number',
        'inward_quantity',
        'outward_quantity',
        'balance_quantity',
        'warehouse',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'transaction_time' => 'datetime:H:i',
    ];

    // Accessor for transaction type badge
    public function getTransactionTypeBadgeAttribute()
    {
        return match($this->transaction_type) {
            'inward' => 'bg-green-100 text-green-800',
            'outward' => 'bg-red-100 text-red-800',
            'transfer' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Scope for filtering by transaction type
    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    // Scope for filtering by warehouse
    public function scopeByWarehouse($query, $warehouse)
    {
        return $query->where('warehouse', $warehouse);
    }
}