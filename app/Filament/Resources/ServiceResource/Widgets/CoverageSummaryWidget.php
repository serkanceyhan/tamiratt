<?php

namespace App\Filament\Resources\ServiceResource\Widgets;

use App\Models\Location;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class CoverageSummaryWidget extends Widget
{
    protected static string $view = 'filament.resources.service-resource.widgets.coverage-summary';
    
    public ?Model $record = null;
    
    public function getCities()
    {
        if (!$this->record) {
            return collect();
        }
        
        // SEO sayfalarındaki location ID'leri al
        $locationIds = $this->record->seoPages()
            ->where('is_active', true)
            ->pluck('location_id');
        
        if ($locationIds->isEmpty()) {
            return collect();
        }
        
        // İlçeleri al
        $districts = Location::whereIn('id', $locationIds)
            ->where('type', 'district')
            ->get();
        
        // İllere göre grupla
        $citiesWithDistricts = $districts->groupBy('parent_id')
            ->map(function ($districts, $cityId) {
                $city = Location::find($cityId);
                return [
                    'city' => $city,
                    'districts' => $districts->sortBy('name'),
                    'count' => $districts->count(),
                ];
            })
            ->sortBy('city.name');
        
        return $citiesWithDistricts;
    }
}
