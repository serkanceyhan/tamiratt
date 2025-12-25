<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use App\Models\Location;
use App\Models\SeoPage;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;
    
    protected function afterCreate(): void
    {
        $this->generateSeoPages();
    }
    
    protected function generateSeoPages(): void
    {
        $cityIds = $this->data['coverage_cities'] ?? [];
        
        if (empty($cityIds)) {
            return;
        }
        
        // Seçilen şehirlerin tüm ilçelerini al
        $districts = Location::whereIn('parent_id', $cityIds)
            ->where('type', 'district')
            ->get();
        
        foreach ($districts as $district) {
            SeoPage::create([
                'service_id' => $this->record->id,
                'location_id' => $district->id,
                'slug' => Str::slug($district->slug . '-' . $this->record->slug),
                'is_active' => true,
            ]);
        }
        
        // Başarı mesajı
        Notification::make()
            ->success()
            ->title('SEO Sayfaları Oluşturuldu')
            ->body($districts->count() . ' ilçe için SEO sayfası oluşturuldu.')
            ->send();
    }
}

