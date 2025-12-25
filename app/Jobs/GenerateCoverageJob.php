<?php

namespace App\Jobs;

use App\Models\Location;
use App\Models\Service;
use App\Models\SeoPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class GenerateCoverageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Location $location
    ) {}

    public function handle(): void
    {
        // Eğer şehirse, ilçelerini de aktif yap
        if ($this->location->type === 'city' && $this->location->is_active) {
            $this->location->children()->update(['is_active' => true]);
        }

        // Aktif ilçeleri al (kendi veya çocukları)
        $districts = $this->location->type === 'city' 
            ? $this->location->children()->active()->get()
            :collect([$this->location])->where('is_active', true);

        // Aktif hizmetleri al
        $services = Service::active()->get();

        // Her ilçe x Her hizmet kombinasyonunu oluştur
        foreach ($districts as $district) {
            foreach ($services as $service) {
                $slug = Str::slug($district->slug . '-' . $service->slug);

                SeoPage::firstOrCreate(
                    [
                        'service_id' => $service->id,
                        'location_id' => $district->id,
                    ],
                    [
                        'slug' => $slug,
                        'is_active' => true,
                    ]
                );
            }
        }

        \Log::info("Coverage generated for: {$this->location->name}");
    }
}
