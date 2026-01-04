<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_code',
        'material_request_id',
        'name',
        'description',
        'specification',
        'unit',
        'quantity',
        'approved_quantity',
        'received_quantity',
        'consumed_quantity',
        'remaining_quantity',
        'unit_price',
        'total_price',
        'status',
        'quality',
        'supplier',
        'brand',
        'model_number',
        'serial_number',
        'notes',
        'technical_specs',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'approved_quantity' => 'integer',
        'received_quantity' => 'integer',
        'consumed_quantity' => 'integer',
        'remaining_quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'technical_specs' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($material) {
            if (empty($material->material_code)) {
                $material->material_code = 'MAT-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            // Calculate total price
            $material->total_price = $material->quantity * $material->unit_price;
            $material->remaining_quantity = $material->quantity;
        });

        static::updating(function ($material) {
            // Calculate total price
            $material->total_price = $material->quantity * $material->unit_price;
            $material->remaining_quantity = $material->received_quantity - $material->consumed_quantity;
        });
    }

    // Relationships
    public function materialRequest(): BelongsTo
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    // Accessors
    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format($this->unit_price, 2);
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return number_format($this->total_price, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'requested' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'ordered' => 'bg-blue-100 text-blue-800',
            'received' => 'bg-green-100 text-green-800',
            'consumed' => 'bg-purple-100 text-purple-800',
            'returned' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getQualityBadgeAttribute(): string
    {
        if (!$this->quality) return 'bg-gray-100 text-gray-800';

        $badges = [
            'excellent' => 'bg-green-100 text-green-800',
            'good' => 'bg-blue-100 text-blue-800',
            'average' => 'bg-yellow-100 text-yellow-800',
            'poor' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->quality] ?? 'bg-gray-100 text-gray-800';
    }

    public function getConsumptionPercentageAttribute(): float
    {
        if ($this->received_quantity <= 0) return 0;
        
        return round(($this->consumed_quantity / $this->received_quantity) * 100, 2);
    }

    public function getReceiptPercentageAttribute(): float
    {
        if ($this->approved_quantity <= 0) return 0;
        
        return round(($this->received_quantity / $this->approved_quantity) * 100, 2);
    }

    public function getDisplaySpecificationAttribute(): string
    {
        return $this->specification ?: $this->description ?: 'N/A';
    }

    // Methods
    public function markAsOrdered(): void
    {
        $this->update(['status' => 'ordered']);
    }

    public function markAsReceived(int $quantity = null, string $quality = null): void
    {
        $updateData = ['status' => 'received'];
        
        if ($quantity !== null) {
            $updateData['received_quantity'] = $quantity;
            $this->remaining_quantity = $quantity;
        }

        if ($quality !== null) {
            $updateData['quality'] = $quality;
        }

        $this->update($updateData);
    }

    public function consume(int $quantity): void
    {
        $newConsumed = $this->consumed_quantity + $quantity;
        
        if ($newConsumed <= $this->received_quantity) {
            $this->update([
                'consumed_quantity' => $newConsumed,
                'remaining_quantity' => $this->received_quantity - $newConsumed,
                'status' => $newConsumed >= $this->received_quantity ? 'consumed' : 'received',
            ]);
        }
    }

    public function return(int $quantity): void
    {
        if ($quantity <= $this->remaining_quantity) {
            $this->update([
                'remaining_quantity' => $this->remaining_quantity - $quantity,
                'status' => $this->remaining_quantity <= 0 ? 'consumed' : 'received',
            ]);
        }
    }

    public function approve(int $quantity = null): void
    {
        $approvedQuantity = $quantity ?? $this->quantity;
        
        $this->update([
            'approved_quantity' => $approvedQuantity,
            'status' => 'approved',
        ]);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByQuality($query, $quality)
    {
        return $query->where('quality', $quality);
    }

    public function scopeBySupplier($query, $supplier)
    {
        return $query->where('supplier', 'like', "%{$supplier}%");
    }

    public function scopeConsumed($query)
    {
        return $query->where('consumed_quantity', '>', 0);
    }

    public function scopeAvailable($query)
    {
        return $query->where('remaining_quantity', '>', 0);
    }

    public function scopeOverdueOrdered($query)
    {
        return $query->where('status', 'ordered')
                    ->where('created_at', '<', now()->subDays(7));
    }
}