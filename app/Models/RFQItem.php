<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RFQItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rfq_id',
        'product_id',
        'item_name',
        'description',
        'specifications',
        'quantity',
        'unit',
        'estimated_price',
        'remarks',
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
    ];

    // Relationships
    public function rfq(): BelongsTo
    {
        return $this->belongsTo(RFQ::class, 'rfq_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}