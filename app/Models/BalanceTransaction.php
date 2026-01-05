<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BalanceTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'type',
        'amount',
        'description',
        'related_quote_id',
        'related_package_id',
        'payment_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Transaction types
    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';

    /**
     * The provider this transaction belongs to
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * The quote related to this transaction (if debit for unlock)
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'related_quote_id');
    }

    /**
     * The package related to this transaction (if credit for purchase)
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'related_package_id');
    }

    /**
     * Scope for credit transactions
     */
    public function scopeCredits($query)
    {
        return $query->where('type', self::TYPE_CREDIT);
    }

    /**
     * Scope for debit transactions
     */
    public function scopeDebits($query)
    {
        return $query->where('type', self::TYPE_DEBIT);
    }
}
