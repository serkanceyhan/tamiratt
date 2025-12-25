<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = ['name', 'slug', 'short_description', 'master_content', 'is_active', 'parent_id', 'show_on_homepage', 'icon'];

    protected $casts = [
        'is_active' => 'boolean',
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
        return $this->belongsToMany(Location::class, 'seo_pages')
                    ->using(SeoPage::class)
                    ->withPivot(['slug', 'is_active', 'custom_content', 'custom_hero_title'])
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
}
