<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'description', 'category', 'unit', 
        'price', 'cost_price', 'tax_rate', 'discount_percentage',
        'stock_quantity', 'min_stock_level', 'max_stock_level',
        'brand', 'model', 'status', 'specifications', 'warranty_period',
        'purchase_price', 'selling_price', 'current_stock', 
        'hsn_code', 'gst_rate', 'is_active', 'image', 'created_by'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
        'warranty_period' => 'integer',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'gst_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_quantity <= min_stock_level');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock_quantity <= $this->min_stock_level) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockStatusBadgeAttribute()
    {
        $badges = [
            'out_of_stock' => 'bg-red-100 text-red-800',
            'low_stock' => 'bg-yellow-100 text-yellow-800',
            'in_stock' => 'bg-green-100 text-green-800',
        ];

        return $badges[$this->stock_status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getProfitMarginAttribute()
    {
        if ($this->cost_price == 0) return 0;
        return round((($this->price - $this->cost_price) / $this->cost_price) * 100, 2);
    }
}
