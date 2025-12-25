<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'parent_id'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}
