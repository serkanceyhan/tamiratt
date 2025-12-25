<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoPage extends Pivot
{
    protected $table = 'seo_pages';
    
    protected $fillable = [
        'service_id',
        'location_id',
        'slug',
        'is_active',
        'custom_hero_title',
        'custom_content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public $incrementing = true;

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
