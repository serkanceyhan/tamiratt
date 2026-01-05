<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TurkeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Adds Istanbul and Ankara with their districts
     */
    public function run(): void
    {
        $cities = [
            'İstanbul' => [
                'Kadıköy', 'Beşiktaş', 'Şişli', 'Bakırköy', 'Ataşehir', 
                'Maltepe', 'Kartal', 'Pendik', 'Ümraniye', 'Üsküdar',
                'Beyoğlu', 'Sarıyer', 'Beykoz', 'Fatih', 'Eyüpsultan',
                'Başakşehir', 'Esenyurt', 'Beylikdüzü', 'Küçükçekmece', 'Bağcılar'
            ],
            'Ankara' => [
                'Çankaya', 'Keçiören', 'Yenimahalle', 'Mamak', 'Etimesgut',
                'Sincan', 'Altındağ', 'Pursaklar', 'Gölbaşı', 'Polatlı'
            ],
            'İzmir' => [
                'Konak', 'Karşıyaka', 'Bornova', 'Buca', 'Çiğli',
                'Bayraklı', 'Gaziemir', 'Balçova', 'Narlıdere', 'Karabağlar'
            ],
        ];

        foreach ($cities as $cityName => $districts) {
            // Create city
            $city = Location::firstOrCreate(
                ['slug' => Str::slug($cityName)],
                [
                    'name' => $cityName,
                    'type' => 'city',
                    'is_active' => true,
                ]
            );
            
            $this->command->info("✓ {$cityName} eklendi");
            
            // Create districts
            foreach ($districts as $districtName) {
                Location::firstOrCreate(
                    ['slug' => Str::slug($districtName), 'parent_id' => $city->id],
                    [
                        'name' => $districtName,
                        'type' => 'district',
                        'is_active' => true,
                    ]
                );
            }
            
            $this->command->info("  → " . count($districts) . " ilçe eklendi");
        }
        
        $this->command->info("\n✅ İl ve ilçeler başarıyla yüklendi!");
    }
}

