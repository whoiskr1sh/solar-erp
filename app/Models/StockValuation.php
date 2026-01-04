<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockValuation extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'item_name',
        'item_code',
        'quantity',
        'unit_cost',
        'total_value',
        'warehouse',
        'last_updated',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total_value' => 'decimal:2',
        'last_updated' => 'date',
    ];

    // Accessor for formatted total value
    public function getFormattedTotalValueAttribute()
    {
        return '₹' . number_format($this->total_value, 2);
    }

    // Accessor for formatted unit cost
    public function getFormattedUnitCostAttribute()
    {
        return '₹' . number_format($this->unit_cost, 2);
    }
}