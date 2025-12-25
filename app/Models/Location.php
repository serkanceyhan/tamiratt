<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Location extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'parent_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($location) {
            if (empty($location->slug)) {
                $location->slug = Str::slug($location->name);
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id')->orderBy('name');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'seo_pages')
                    ->using(SeoPage::class)
                    ->withPivot(['slug', 'is_active', 'custom_content', 'custom_hero_title'])
                    ->withTimestamps();
    }
    
    public function seoPages(): HasMany
    {
        return $this->hasMany(SeoPage::class, 'location_id');
    }

    public function scopeCities($query)
    {
        return $query->where('type', 'city');
    }

    public function scopeDistricts($query)
    {
        return $query->where('type', 'district');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
