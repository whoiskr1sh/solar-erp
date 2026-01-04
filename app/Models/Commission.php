<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_number', 'channel_partner_id', 'project_id', 'invoice_id', 'quotation_id',
        'reference_type', 'reference_number', 'base_amount', 'commission_rate', 'commission_amount',
        'paid_amount', 'pending_amount', 'status', 'payment_status', 'due_date', 'paid_date',
        'description', 'notes', 'payment_details', 'documents', 'approved_by', 'approved_at', 'created_by'
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'payment_details' => 'array',
        'documents' => 'array',
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($commission) {
            if (empty($commission->commission_number)) {
                $commission->commission_number = $commission->generateCommissionNumber();
            }
            if (empty($commission->created_by)) {
                $commission->created_by = auth()->id();
            }
            $commission->calculateCommission();
        });

        static::updating(function ($commission) {
            $commission->calculateCommission();
        });
    }

    // Relationships
    public function channelPartner(): BelongsTo
    {
        return $this->belongsTo(ChannelPartner::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeDisputed($query)
    {
        return $query->where('status', 'disputed');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    public function scopePartial($query)
    {
        return $query->where('payment_status', 'partial');
    }

    public function scopePaidStatus($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeForChannelPartner($query, $partnerId)
    {
        return $query->where('channel_partner_id', $partnerId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('payment_status', '!=', 'paid');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'paid' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'disputed' => 'bg-orange-100 text-orange-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPaymentStatusBadgeAttribute(): string
    {
        $badges = [
            'unpaid' => 'bg-red-100 text-red-800',
            'partial' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
        ];

        return $badges[$this->payment_status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getFormattedBaseAmountAttribute(): string
    {
        return 'Rs. ' . number_format($this->base_amount, 2);
    }

    public function getFormattedCommissionAmountAttribute(): string
    {
        return 'Rs. ' . number_format($this->commission_amount, 2);
    }

    public function getFormattedPaidAmountAttribute(): string
    {
        return 'Rs. ' . number_format($this->paid_amount, 2);
    }

    public function getFormattedPendingAmountAttribute(): string
    {
        return 'Rs. ' . number_format($this->pending_amount, 2);
    }

    public function getFormattedCommissionRateAttribute(): string
    {
        return $this->commission_rate . '%';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->due_date < now() && $this->payment_status !== 'paid';
    }

    public function getIsDueSoonAttribute(): bool
    {
        return $this->due_date && $this->due_date->diffInDays(now()) <= 7 && $this->payment_status !== 'paid';
    }

    public function getReferenceDisplayAttribute(): string
    {
        if ($this->reference_number) {
            return $this->reference_type . ' #' . $this->reference_number;
        }
        return 'Manual Entry';
    }

    // Helper methods
    public function generateCommissionNumber(): string
    {
        $year = now()->year;
        $month = now()->format('m');
        $count = static::whereYear('created_at', $year)
                      ->whereMonth('created_at', $month)
                      ->count() + 1;
        
        return "COMM-{$year}{$month}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function calculateCommission(): void
    {
        $this->commission_amount = $this->base_amount * ($this->commission_rate / 100);
        $this->pending_amount = $this->commission_amount - $this->paid_amount;
        
        // Update payment status
        if ($this->paid_amount == 0) {
            $this->payment_status = 'unpaid';
        } elseif ($this->paid_amount >= $this->commission_amount) {
            $this->payment_status = 'paid';
            $this->status = 'paid';
            $this->paid_date = now();
        } else {
            $this->payment_status = 'partial';
        }
    }

    public function approve(User $approver): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now()
        ]);
    }

    public function cancel(User $user): void
    {
        $this->update([
            'status' => 'cancelled',
            'approved_by' => $user->id,
            'approved_at' => now()
        ]);
    }

    public function dispute(User $user): void
    {
        $this->update([
            'status' => 'disputed',
            'approved_by' => $user->id,
            'approved_at' => now()
        ]);
    }

    public function addPayment($amount, $paymentDetails = []): void
    {
        $newPaidAmount = $this->paid_amount + $amount;
        
        $existingDetails = $this->payment_details ?? [];
        
        // If existing details is not an array, convert it to array format
        if (!is_array($existingDetails)) {
            $existingDetails = [];
        }
        
        // Add new payment details as an array element
        $existingDetails[] = $paymentDetails;
        
        $this->update([
            'paid_amount' => $newPaidAmount,
            'payment_details' => $existingDetails
        ]);
        
        $this->calculateCommission();
    }

    public function markAsPaid($paymentDetails = []): void
    {
        $existingDetails = $this->payment_details ?? [];
        
        // If existing details is not an array, convert it to array format
        if (!is_array($existingDetails)) {
            $existingDetails = [];
        }
        
        // Add new payment details as an array element
        $existingDetails[] = $paymentDetails;
        
        $this->update([
            'paid_amount' => $this->commission_amount,
            'status' => 'paid',
            'payment_status' => 'paid',
            'paid_date' => now(),
            'payment_details' => $existingDetails
        ]);
    }
}