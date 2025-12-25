<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use App\Models\Location;
use App\Models\SeoPage;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Mevcut SEO sayfalarından il bilgilerini yükle
        $districtIds = $this->record->seoPages()
            ->where('is_active', true)
            ->pluck('location_id')
            ->toArray();
        
        if (!empty($districtIds)) {
            // İlçelerin hangi illere ait olduğunu bul
            $cityIds = Location::whereIn('id', $districtIds)
                ->where('type', 'district')
                ->pluck('parent_id')
                ->unique()
                ->toArray();
            
            $data['coverage_cities'] = $cityIds;
        }
        
        return $data;
    }
    
    protected function afterSave(): void
    {
        $this->updateSeoPages();
    }
    
    protected function updateSeoPages(): void
    {
        $cityIds = $this->data['coverage_cities'] ?? [];
        
        // Mevcut tüm SEO sayfalarını sil
        $this->record->seoPages()->delete();
        
        if (empty($cityIds)) {
            Notification::make()
                ->info()
                ->title('Kapsama Alanı Temizlendi')
                ->send();
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
        
        Notification::make()
            ->success()
            ->title('SEO Sayfaları Güncellendi')
            ->body($districts->count() . ' ilçe için SEO sayfası güncellendi.')
            ->send();
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            ServiceResource\Widgets\CoverageSummaryWidget::class,
        ];
    }
}
