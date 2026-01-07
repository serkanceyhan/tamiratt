<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Provider extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'company_name',
        'tax_number',
        'phone',
        'verification_status',
        'verification_notes',
        'service_areas',
        'service_categories',
        'balance',
        'is_active',
        'activation_token',
        'activated_at',
    ];

    protected $casts = [
        'service_areas' => 'array',
        'service_categories' => 'array',
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
    ];

    // Verification status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * The user account associated with this provider
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Balance transactions for this provider
     */
    public function balanceTransactions(): HasMany
    {
        return $this->hasMany(BalanceTransaction::class);
    }

    /**
     * Quotes purchased (unlocked) by this provider
     */
    public function quotePurchases(): HasMany
    {
        return $this->hasMany(QuotePurchase::class);
    }

    /**
     * Offers made by this provider
     */
    public function offers(): HasMany
    {
        return $this->hasMany(ProviderOffer::class);
    }

    /**
     * Media collections for verification documents
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('verification_documents')
            ->useDisk('public');

        $this->addMediaCollection('profile_photo')
            ->singleFile()
            ->useDisk('public');
    }

    /**
     * Check if provider is approved
     */
    public function isApproved(): bool
    {
        return $this->verification_status === self::STATUS_APPROVED;
    }

    /**
     * Check if provider is pending review
     */
    public function isPending(): bool
    {
        return $this->verification_status === self::STATUS_PENDING;
    }

    /**
     * Check if provider has sufficient balance
     */
    public function hasBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    /**
     * Deduct balance for lead unlock
     */
    public function deductBalance(float $amount, string $description, ?int $quoteId = null): BalanceTransaction
    {
        $this->decrement('balance', $amount);

        return $this->balanceTransactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'description' => $description,
            'related_quote_id' => $quoteId,
        ]);
    }

    /**
     * Add balance from package purchase
     */
    public function addBalance(float $amount, string $description, ?int $packageId = null, ?string $paymentId = null): BalanceTransaction
    {
        $this->increment('balance', $amount);

        return $this->balanceTransactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'description' => $description,
            'related_package_id' => $packageId,
            'payment_id' => $paymentId,
        ]);
    }

    /**
     * Check if provider has purchased (unlocked) a specific quote
     */
    public function hasPurchasedQuote(int $quoteId): bool
    {
        return $this->quotePurchases()->where('quote_id', $quoteId)->exists();
    }

    /**
     * Get service category names
     */
    public function getServiceCategoryNamesAttribute(): array
    {
        if (empty($this->service_categories)) {
            return [];
        }

        return Service::whereIn('id', $this->service_categories)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Get service area names
     */
    public function getServiceAreaNamesAttribute(): array
    {
        if (empty($this->service_areas)) {
            return [];
        }

        return Location::whereIn('id', $this->service_areas)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Scope for approved providers
     */
    public function scopeApproved($query)
    {
        return $query->where('verification_status', self::STATUS_APPROVED);
    }

    /**
     * Scope for pending providers
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', self::STATUS_PENDING);
    }

    /**
     * Scope for active providers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->approved();
    }
}
