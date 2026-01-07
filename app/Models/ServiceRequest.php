<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ServiceRequest extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'service_id',
        'sub_service_id',
        'answers',
        'description',
        'location_id',
        'address',
        'latitude',
        'longitude',
        'preferred_date',
        'preferred_time',
        'urgency',
        'budget_min',
        'budget_max',
        'phone',
        'phone_verified_at',
        'contact_name',
        'email',
        'status',
        'media_token',
        'lead_price',
        'purchased_by',
        'purchased_at',
        'expires_at',
        'ip_address',
        'user_agent',
        'source',
    ];

    protected $casts = [
        'answers' => 'array',
        'preferred_date' => 'date',
        'phone_verified_at' => 'datetime',
        'purchased_at' => 'datetime', 
        'expires_at' => 'datetime',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'lead_price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_VERIFICATION = 'pending_verification';
    const STATUS_OPEN = 'open';
    const STATUS_LOCKED = 'locked';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    // Time preference constants
    const TIME_MORNING = 'morning';
    const TIME_AFTERNOON = 'afternoon';
    const TIME_EVENING = 'evening';
    const TIME_FLEXIBLE = 'flexible';

    // Urgency constants
    const URGENCY_NORMAL = 'normal';
    const URGENCY_URGENT = 'urgent';
    const URGENCY_EMERGENCY = 'emergency';

    /**
     * User who created this request (may be ghost user)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Main service category
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Sub-service (child of main service)
     */
    public function subService(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'sub_service_id');
    }

    /**
     * Sub-services (multiple selection)
     */
    public function subServices()
    {
        return $this->belongsToMany(Service::class, 'service_request_sub_services', 'service_request_id', 'service_id');
    }

    /**
     * Location (city/district)
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Provider who purchased/locked this lead
     */
    public function purchaser(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'purchased_by');
    }

    /**
     * Provider offers for this request
     */
    public function offers(): HasMany
    {
        return $this->hasMany(ProviderOffer::class, 'quote_id'); // Reusing existing offers table
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('request_photos')
            ->useDisk('public')
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->format('webp');
                    
                $this->addMediaConversion('preview')
                    ->width(800)
                    ->height(800)
                    ->format('webp');
            });
    }

    // === Status Helpers ===

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPendingVerification(): bool
    {
        return $this->status === self::STATUS_PENDING_VERIFICATION;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isLocked(): bool
    {
        return $this->status === self::STATUS_LOCKED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED || 
               ($this->expires_at && $this->expires_at->isPast());
    }

    public function isAvailableForPurchase(): bool
    {
        return $this->isOpen() && !$this->isExpired() && is_null($this->purchased_by);
    }

    // === Scopes ===

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeForService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    public function scopeForLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_OPEN)
                    ->whereNull('purchased_by')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    // === Helpers ===

    /**
     * Get formatted preferred time
     */
    public function getPreferredTimeLabel(): string
    {
        return match($this->preferred_time) {
            self::TIME_MORNING => 'Sabah (09:00 - 12:00)',
            self::TIME_AFTERNOON => 'Öğleden Sonra (12:00 - 17:00)',
            self::TIME_EVENING => 'Akşam (17:00 - 21:00)',
            self::TIME_FLEXIBLE => 'Esnek',
            default => 'Belirtilmemiş',
        };
    }

    /**
     * Get formatted urgency
     */
    public function getUrgencyLabel(): string
    {
        return match($this->urgency) {
            self::URGENCY_NORMAL => 'Normal',
            self::URGENCY_URGENT => 'Acil',
            self::URGENCY_EMERGENCY => 'Çok Acil',
            default => 'Normal',
        };
    }

    /**
     * Get budget range as string
     */
    public function getBudgetRange(): ?string
    {
        if ($this->budget_min && $this->budget_max) {
            return number_format($this->budget_min, 0) . ' - ' . number_format($this->budget_max, 0) . ' ₺';
        }
        if ($this->budget_max) {
            return 'Maks. ' . number_format($this->budget_max, 0) . ' ₺';
        }
        if ($this->budget_min) {
            return 'Min. ' . number_format($this->budget_min, 0) . ' ₺';
        }
        return null;
    }
}
