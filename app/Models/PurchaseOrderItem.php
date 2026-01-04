<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'item_name',
        'description',
        'quantity',
        'unit_price',
        'total_price',
        'unit',
        'received_quantity',
        'pending_quantity',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getCompletionPercentageAttribute(): float
    {
        if ($this->quantity <= 0) return 0;
        return round(($this->received_quantity / $this->quantity) * 100, 2);
    }

    public function getIsFullyReceivedAttribute(): bool
    {
        return $this->received_quantity >= $this->quantity;
    }

    public function getIsPartiallyReceivedAttribute(): bool
    {
        return $this->received_quantity > 0 && $this->received_quantity < $this->quantity;
    }

    // Boot method to calculate pending quantity
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->pending_quantity = $item->quantity;
        });

        static::updating(function ($item) {
            $item->pending_quantity = $item->quantity - $item->received_quantity;
        });
    }
}