<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // Automatically update last_modified_at when quotation is updated
        static::updating(function ($quotation) {
            $quotation->last_modified_at = now();
        });
    }

    protected $fillable = [
        'quotation_number', 'quotation_type', 'quotation_date', 'valid_until', 'client_id', 
        'project_id', 'channel_partner_id', 'subtotal', 'tax_amount', 'total_amount', 
        'status', 'notes', 'terms_conditions', 'created_by', 'last_modified_at', 'follow_up_date',
        'parent_quotation_id', 'revision_number', 'is_revision', 'is_latest'
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
        'follow_up_date' => 'date',
        'last_modified_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_revision' => 'boolean',
        'is_latest' => 'boolean',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'client_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function channelPartner(): BelongsTo
    {
        return $this->belongsTo(ChannelPartner::class);
    }

    public function parentQuotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'parent_quotation_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(Quotation::class, 'parent_quotation_id')->orderBy('revision_number', 'desc');
    }

    public function allRevisions()
    {
        if ($this->parent_quotation_id) {
            return Quotation::where('parent_quotation_id', $this->parent_quotation_id)
                ->orWhere('id', $this->parent_quotation_id)
                ->orderBy('revision_number', 'asc')
                ->get();
        }
        return Quotation::where('parent_quotation_id', $this->id)
            ->orWhere('id', $this->id)
            ->orderBy('revision_number', 'asc')
            ->get();
    }

    public function getLatestRevision()
    {
        if ($this->parent_quotation_id) {
            return Quotation::where('parent_quotation_id', $this->parent_quotation_id)
                ->orWhere('id', $this->parent_quotation_id)
                ->where('is_latest', true)
                ->first();
        }
        return Quotation::where('parent_quotation_id', $this->id)
            ->orWhere('id', $this->id)
            ->where('is_latest', true)
            ->first() ?? $this;
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', now());
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'sent' => 'bg-blue-100 text-blue-800',
            'accepted' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'expired' => 'bg-orange-100 text-orange-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getIsExpiredAttribute()
    {
        return $this->valid_until < now() && $this->status !== 'accepted' && $this->status !== 'rejected';
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->valid_until < now()) return 0;
        return now()->diffInDays($this->valid_until, false);
    }

    // Check if quotation needs follow-up
    public function needsFollowUp(): bool
    {
        // Don't show expired quotations at the top (check valid_until date and status)
        if ($this->valid_until && $this->valid_until < now()) {
            return false;
        }
        
        if ($this->status === 'expired' || $this->status === 'rejected' || $this->status === 'accepted' || $this->status === 'approved') {
            return false;
        }

        // Check if lead hasn't been updated since quotation was created
        if ($this->client && $this->client->updated_at < $this->created_at) {
            return true;
        }

        // Check if quotation was created/modified more than 10 days ago
        $checkDate = $this->last_modified_at ?? $this->created_at;
        if ($checkDate && now()->diffInDays($checkDate) >= 10) {
            return true;
        }

        return false;
    }

    // Get quotation type label
    public function getQuotationTypeLabelAttribute(): string
    {
        $types = [
            'solar_chakki' => 'Solar Chakki',
            'solar_street_light' => 'Solar Street Light',
            'commercial' => 'Commercial',
            'subsidy_quotation' => 'Subsidy Quotation',
        ];

        return $types[$this->quotation_type] ?? 'N/A';
    }

    /**
     * Convert number to words (Indian numbering system)
     */
    public function numberToWords($number)
    {
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 
                 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 
                 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
        $number = (int) $number;
        
        if ($number == 0) {
            return 'Zero';
        }
        
        if ($number < 20) {
            return $ones[$number];
        }
        
        if ($number < 100) {
            return $tens[(int)($number / 10)] . ($number % 10 ? ' ' . $ones[$number % 10] : '');
        }
        
        if ($number < 1000) {
            return $ones[(int)($number / 100)] . ' Hundred' . ($number % 100 ? ' ' . $this->numberToWords($number % 100) : '');
        }
        
        if ($number < 100000) {
            return $this->numberToWords((int)($number / 1000)) . ' Thousand' . ($number % 1000 ? ' ' . $this->numberToWords($number % 1000) : '');
        }
        
        if ($number < 10000000) {
            return $this->numberToWords((int)($number / 100000)) . ' Lakh' . ($number % 100000 ? ' ' . $this->numberToWords($number % 100000) : '');
        }
        
        return $this->numberToWords((int)($number / 10000000)) . ' Crore' . ($number % 10000000 ? ' ' . $this->numberToWords($number % 10000000) : '');
    }
}