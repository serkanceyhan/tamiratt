<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TurkeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Loads Turkey provinces and districts from API data
     */
    public function run(): void
    {
        $url = 'https://turkiyeapi.dev/api/v1/provinces';
        
        $this->command->info('Turkiye API\'den veri cekiliyor...');
        
        $json = @file_get_contents($url);
        
        if (!$json) {
            $this->command->error('API\'ye baglanilamadi! Varsayilan veriler yukleniyor...');
            $this->loadDefaultData();
            return;
        }
        
        $response = json_decode($json, true);
        
        if (!isset($response['data'])) {
            $this->command->error('API yaniti gecersiz! Varsayilan veriler yukleniyor...');
            $this->loadDefaultData();
            return;
        }
        
        $provinces = $response['data'];
        
        // Sadece Istanbul aktif olacak (baslangicta)
        $activeCities = ['İstanbul'];
        
        $totalDistricts = 0;
        
        foreach ($provinces as $province) {
            $provinceName = $province['name'];
            $isActive = in_array($provinceName, $activeCities);
            
            // Il olustur veya guncelle
            $city = Location::updateOrCreate(
                ['slug' => Str::slug($provinceName), 'type' => 'city'],
                [
                    'name' => $provinceName,
                    'is_active' => $isActive,
                ]
            );
            
            // Ilceleri olustur
            if (isset($province['districts']) && is_array($province['districts'])) {
                foreach ($province['districts'] as $district) {
                    $districtName = $district['name'];
                    
                    Location::updateOrCreate(
                        ['slug' => Str::slug($districtName) . '-' . $city->id, 'parent_id' => $city->id],
                        [
                            'name' => $districtName,
                            'type' => 'district',
                            'is_active' => $isActive,
                        ]
                    );
                    
                    $totalDistricts++;
                }
            }
        }
        
        $this->command->info('');
        $this->command->info('Turkiye verisi basariyla yuklendi!');
        $this->command->info('Toplam: ' . count($provinces) . ' il, ' . $totalDistricts . ' ilce');
        $this->command->info('Aktif: ' . implode(', ', $activeCities));
    }
    
    /**
     * Load default data if API is not available
     */
    private function loadDefaultData(): void
    {
        $cities = [
            'İstanbul' => [
                'Adalar', 'Arnavutköy', 'Ataşehir', 'Avcılar', 'Bağcılar', 'Bahçelievler',
                'Bakırköy', 'Başakşehir', 'Bayrampaşa', 'Beşiktaş', 'Beykoz', 'Beylikdüzü',
                'Beyoğlu', 'Büyükçekmece', 'Çatalca', 'Çekmeköy', 'Esenler', 'Esenyurt',
                'Eyüpsultan', 'Fatih', 'Gaziosmanpaşa', 'Güngören', 'Kadıköy', 'Kağıthane',
                'Kartal', 'Küçükçekmece', 'Maltepe', 'Pendik', 'Sancaktepe', 'Sarıyer',
                'Silivri', 'Sultanbeyli', 'Sultangazi', 'Şile', 'Şişli', 'Tuzla',
                'Ümraniye', 'Üsküdar', 'Zeytinburnu'
            ],
        ];

        foreach ($cities as $cityName => $districts) {
            $city = Location::updateOrCreate(
                ['slug' => Str::slug($cityName), 'type' => 'city'],
                [
                    'name' => $cityName,
                    'is_active' => true,
                ]
            );
            
            foreach ($districts as $districtName) {
                Location::updateOrCreate(
                    ['slug' => Str::slug($districtName) . '-' . $city->id, 'parent_id' => $city->id],
                    [
                        'name' => $districtName,
                        'type' => 'district',
                        'is_active' => true,
                    ]
                );
            }
            
            $this->command->info($cityName . ': ' . count($districts) . ' ilce eklendi');
        }
        
        $this->command->info('');
        $this->command->info('Varsayilan veriler yuklendi!');
    }
}
