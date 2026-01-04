<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QualityCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'qc_number',
        'item_name',
        'item_code',
        'vendor_name',
        'inspector_name',
        'inspector_designation',
        'checked_by',
        'status',
        'qc_date',
        'remarks',
    ];

    protected $casts = [
        'qc_date' => 'date',
    ];

    // Relationships
    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'passed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Scope for filtering by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for filtering by inspector
    public function scopeByInspector($query, $inspector)
    {
        return $query->where('inspector_name', $inspector);
    }

    // Scope for filtering by checked by user
    public function scopeByCheckedBy($query, $userId)
    {
        return $query->where('checked_by', $userId);
    }
}