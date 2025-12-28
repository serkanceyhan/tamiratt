<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Service;
use Illuminate\Database\Seeder;

class AttachIstanbulToServicesSeeder extends Seeder
{
    public function run(): void
    {
        // İstanbul'u bul
        $istanbul = Location::where('slug', 'istanbul')->first();
        
        if (!$istanbul) {
            $this->command->error('❌ İstanbul bulunamadı!');
            return;
        }

        // Tüm hizmetlere İstanbul'u ekle
        $services = Service::all();
        
        foreach ($services as $service) {
            $service->locations()->syncWithoutDetaching([$istanbul->id]);
        }

        $this->command->info('✅ ' . $services->count() . ' hizmete İstanbul kapsama alanı eklendi!');
    }
}
