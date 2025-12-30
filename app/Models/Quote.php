<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Quote extends Model implements HasMedia
{
    use LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'company_name',
        'name',
        'email',
        'service_type',
        'message',
        'file_path',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['company_name', 'name', 'email', 'service_type', 'message', 'file_path'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Register media collections for file attachments
     * 
     * MARKETPLACE MODEL: Customers can upload damage/before photos
     * Maximum 5 photos per quote for detailed assessment
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('damage_photos')
            ->useFallbackUrl('/images/placeholder.jpg')
            ->useFallbackPath(public_path('/images/placeholder.jpg'))
            ->registerMediaConversions(function () {
                $this->addMediaConversion('admin_preview')
                    ->width(800)
                    ->height(800)
                    ->format('webp')
                    ->nonQueued(); // Immediate conversion for admin review
                
                $this->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->format('webp')
                    ->performOnCollections('damage_photos');
            });
    }
}
