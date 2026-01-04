<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_id',
        'warehouse_name',
        'warehouse_location',
        'auditor_name',
        'auditor_designation',
        'audited_by',
        'status',
        'start_date',
        'end_date',
        'items_audited',
        'remarks',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function auditedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'audited_by');
    }

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Scope for filtering by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for filtering by warehouse
    public function scopeByWarehouse($query, $warehouse)
    {
        return $query->where('warehouse_name', $warehouse);
    }

    // Scope for filtering by audited by user
    public function scopeByAuditedBy($query, $userId)
    {
        return $query->where('audited_by', $userId);
    }
}