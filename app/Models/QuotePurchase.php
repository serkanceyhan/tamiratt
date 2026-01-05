<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'provider_id',
        'amount_paid',
        'purchased_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    /**
     * The quote that was purchased
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * The provider who purchased this quote
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
