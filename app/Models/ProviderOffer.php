<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'service_request_id',  // New field for marketplace flow
        'provider_id',
        'price',
        'timeline_days',
        'description',
        'notes',
        'status',
        'accepted_at',
        'rejected_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'timeline_days' => 'integer',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // Offer status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WITHDRAWN = 'withdrawn';

    /**
     * The quote this offer is for (legacy)
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * The service request this offer is for (new marketplace flow)
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * The provider who made this offer
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Check if offer is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if offer was accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    /**
     * Accept this offer
     */
    public function accept(): void
    {
        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'accepted_at' => now(),
        ]);
    }

    /**
     * Reject this offer
     */
    public function reject(): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_at' => now(),
        ]);
    }

    /**
     * Scope for pending offers
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for accepted offers
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }
}
