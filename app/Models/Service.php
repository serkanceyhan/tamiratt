<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = ['name', 'slug', 'short_description', 'description_placeholder', 'master_content', 'master_hero_title', 'is_active', 'parent_id', 'show_on_homepage', 'icon', 'estimated_price_min', 'estimated_price_max', 'lead_price'];

    protected $casts = [
        'is_active' => 'boolean',
        'estimated_price_min' => 'decimal:2',
        'estimated_price_max' => 'decimal:2',
        'lead_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'location_service')
                    ->withTimestamps();
    }
    
    public function seoPages()
    {
        return $this->hasMany(SeoPage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany(Service::class, 'parent_id');
    }
    
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get questions for this service
     */
    public function questions()
    {
        return $this->hasMany(ServiceQuestion::class)->ordered();
    }

    /**
     * Get service requests for this service
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Get estimated price range display
     */
    public function getEstimatedPriceRange(): ?string
    {
        if ($this->estimated_price_min && $this->estimated_price_max) {
            return number_format($this->estimated_price_min, 0) . ' - ' . number_format($this->estimated_price_max, 0) . ' ₺';
        }
        return null;
    }
}
