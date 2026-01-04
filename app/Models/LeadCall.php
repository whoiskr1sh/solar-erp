<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'caller_id',
        'assigned_user_id',
        'status',
        'notes',
        'duration_seconds',
        'called_at',
    ];

    protected $casts = [
        'called_at' => 'datetime',
    ];

    /**
     * Get the lead this call belongs to
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the user who made the call (Person A)
     */
    public function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    /**
     * Get the user the lead is assigned to (Person B - who gets the incentive)
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
