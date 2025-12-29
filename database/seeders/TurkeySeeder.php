<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TurkeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Fetches complete Turkey data (81 provinces, 973 districts) from turkiyeapi.dev
     */
    public function run(): void
    {
        // turkiyeapi.dev'den veri çek
        $url = 'https://turkiyeapi.dev/api/v1/provinces';
        $json = file_get_contents($url);
        $response = json_decode($json, true);
        
        if (!isset($response['data'])) {
            $this->command->error("API'den veri çekilemedi!");
            return;
        }
        
        $provinces = $response['data'];
        
        $this->command->info('Toplam ' . count($provinces) . ' il yükleniyor...');
        
        // Sadece İstanbul ve Ankara'yı aktif et
        $activeCities = ['İstanbul', 'Ankara'];
        
        foreach ($provinces as $province) {
            $provinceName = $province['name'];
            $isActive = in_array($provinceName, $activeCities);
            
            // İl oluştur
            $city = Location::create([
                'name' => $provinceName,
                'slug' => Str::slug($provinceName),
                'type' => 'city',
                'is_active' => $isActive,
            ]);
            
            $this->command->info("✓ {$provinceName} " . ($isActive ? '(Aktif)' : '(Pasif)'));
            
            // İlçeleri oluştur
            if (isset($province['districts']) && is_array($province['districts'])) {
                foreach ($province['districts'] as $district) {
                    $districtName = $district['name'];
                    
                    Location::create([
                        'name' => $districtName,
                        'slug' => Str::slug($districtName),
                        'type' => 'district',
                        'parent_id' => $city->id,
                        'is_active' => $isActive, // İl ile aynı durum
                    ]);
                }
                
                $this->command->info("  → " . count($province['districts']) . " ilçe eklendi");
            }
        }
        
        $this->command->info("\n✅ Tüm il ve ilçeler başarıyla yüklendi!");
        $this->command->info("📊 Toplam: " . count($provinces) . " il");
        $this->command->info("🟢 Aktif: " . count($activeCities) . " il (İstanbul, Ankara)");
    }
}
