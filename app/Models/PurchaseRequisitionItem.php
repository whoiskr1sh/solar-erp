<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseRequisitionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_requisition_id',
        'product_id',
        'item_name',
        'description',
        'specifications',
        'quantity',
        'estimated_unit_price',
        'estimated_total_price',
        'unit',
        'remarks',
    ];

    protected $casts = [
        'estimated_unit_price' => 'decimal:2',
        'estimated_total_price' => 'decimal:2',
    ];

    // Relationships
    public function purchaseRequisition(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Boot method to calculate total price
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if ($item->estimated_unit_price && $item->quantity) {
                $item->estimated_total_price = $item->estimated_unit_price * $item->quantity;
            }
        });

        static::updating(function ($item) {
            if ($item->estimated_unit_price && $item->quantity) {
                $item->estimated_total_price = $item->estimated_unit_price * $item->quantity;
            }
        });
    }
}