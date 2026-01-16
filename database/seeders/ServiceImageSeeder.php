<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceImageSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Real slugs from database mapped to files in public/icons-images
        $serviceImages = [
            // AHŞAP MOBİLYA TAMİRİ
            'dolap-kilit-ray-tamiri' => [
                'hero_image' => 'services/hero-images/Dolap Kilit ve Ray Tamiri.jpg',
                'icon_svg' => 'services/hero-images/Dolap Kilit ve Ray Sistemi.svg',
            ],
            'genel-ofis-mobilya-tamiri' => [
                'hero_image' => 'services/hero-images/Genel Mobilya Tamiri.jpg',
                'icon_svg' => 'services/hero-images/Genel Mobilya Tamiri.svg',
            ],
            'ofis-mobilya-boya-cila' => [
                'hero_image' => 'services/hero-images/Mobilya Boya ve Cila.jpg',
                'icon_svg' => 'services/hero-images/Mobilya Boya ve Cila.svg',
            ],
            'ofis-masasi-tamiri' => [
                'hero_image' => 'services/hero-images/Ofis Masası Tamiri.jpg',
                'icon_svg' => 'services/hero-images/Ofis Masası Tamiri.svg',
            ],

            // DÖŞEME VE KAPLAMA
            'deri-koltuk-yuz-degisimi' => [
                'hero_image' => 'icons-images/DÖŞEME VE KAPLAMA/Deri Koltuk Yüz Değişimi.jpg',
                'icon_svg' => 'icons-images/DÖŞEME VE KAPLAMA/Deri Koltuk Yüz Değişimi.svg',
            ],
            'kumas-koltuk-kaplama' => [
                'hero_image' => 'icons-images/DÖŞEME VE KAPLAMA/Kumaş Kaplama ve Yenileme.jpg',
                'icon_svg' => 'icons-images/DÖŞEME VE KAPLAMA/Kumaş Kaplama ve Yenileme.svg',
            ],
            'oyuncu-koltugu-kaplama' => [
                'hero_image' => 'icons-images/DÖŞEME VE KAPLAMA/Oyuncu Koltuğu Kaplama.jpg',
                'icon_svg' => 'icons-images/DÖŞEME VE KAPLAMA/Oyuncu Koltuğu Kaplama.svg',
            ],
            'puf-bench-kaplama' => [
                'hero_image' => 'icons-images/DÖŞEME VE KAPLAMA/Puf ve Bench Kaplama.jpg',
                'icon_svg' => 'icons-images/DÖŞEME VE KAPLAMA/Puf ve Bench Kaplama.svg',
            ],

            // MONTAJ-NAKLİYE
            'ofis-mobilya-sokum-demontaj' => [
                'hero_image' => 'services/hero-images/Demontaj.jpg',
                'icon_svg' => 'services/hero-images/Demontaj.svg',
            ],
            'ofis-mobilya-kurulum-montaj' => [
                'hero_image' => 'services/hero-images/Montaj.jpg',
                'icon_svg' => 'services/hero-images/Montaj.svg',
            ],
            'ofis-tasima-nakliye' => [
                'hero_image' => 'services/hero-images/Ofis Taşıma.jpg',
                'icon_svg' => 'services/hero-images/Ofis Taşıma.svg',
            ],

            // OFİS KOLTUĞU TAMİRİ
            'amortisor-degisimi' => [
                'hero_image' => 'services/hero-images/Amortisör Değişimi.jpg',
                'icon_svg' => 'services/hero-images/Amortisör Değişimi.svg',
            ],
            'koltuk-ayak-kolcak-degisimi' => [
                'hero_image' => 'services/hero-images/Koltuk Ayak-Kolçak Değişimi.jpg',
                'icon_svg' => 'services/hero-images/Koltuk Ayak-Kolçak Değişimi.svg',
            ],
            'mekanizma-tekerlek-tamiri' => [
                'hero_image' => 'services/hero-images/Mekanizma-Tekerlek Değişimi.jpg',
                'icon_svg' => 'services/hero-images/Mekanizma & Tekerlek Tamiri.svg',
            ],
            'ofis-sandalyesi-tamiri' => [
                'hero_image' => 'services/hero-images/Ofis Sandalyesi Tamiri.jpg',
                'icon_svg' => 'services/hero-images/Ofis Sandalyesi Tamiri.svg',
            ],

            // OFİS MOBİLYA ÜRETİMİ
            'karsilama-bankosu-imalati' => [
                'hero_image' => 'services/hero-images/Karşılama Bankosu.jpg',
                'icon_svg' => 'services/hero-images/Karşılama Bankosu.svg',
            ],
            'makam-takimi-imalati' => [
                'hero_image' => 'services/hero-images/Makam Takımı İmalatı.jpg',
                'icon_svg' => 'services/hero-images/Makam Takımı İmalatı.svg',
            ],
            'ofis-dolabi-kitaplik-uretimi' => [
                'hero_image' => 'services/hero-images/Ofis Dolabi Üretimi.jpg',
                'icon_svg' => 'services/hero-images/Ofis Dolabı Üretimi.svg',
            ],
            'toplanti-masasi-imalati' => [
                'hero_image' => 'services/hero-images/Toplantı Masası İmalatı.jpg',
                'icon_svg' => 'services/hero-images/Toplantı Masası İmalatı.svg',
            ],
            'workstation-calisma-masasi' => [
                'hero_image' => 'services/hero-images/Workstation.jpg',
                'icon_svg' => 'services/hero-images/Workstation.svg',
            ],

            // YEDEK PARÇA
            'ofis-koltugu-yedek-parca' => [
                'hero_image' => 'services/hero-images/Koltuk Yedek Parçaları.jpg',
                'icon_svg' => 'services/hero-images/Koltuk Yedek Parçaları.svg',
            ],
            'ofis-masa-aksesuarlari' => [
                'hero_image' => 'services/hero-images/Masa Aksesuarları.jpg',
                'icon_svg' => 'services/hero-images/Masa Aksesuarları.svg',
            ],
        ];

        $updated = 0;
        $notFound = 0;

        foreach ($serviceImages as $slug => $images) {
            $service = Service::where('slug', $slug)->first();
            
            if ($service) {
                $service->update([
                    'hero_image' => $images['hero_image'],
                    'icon_svg' => $images['icon_svg'],
                ]);
                
                $this->command->info("✓ Updated: {$service->name} ({$slug})");
                $updated++;
            } else {
                $this->command->warn("✗ Service not found: {$slug}");
                $notFound++;
            }
        }

        $this->command->newLine();
        $this->command->info("✅ Updated {$updated} services");
        if ($notFound > 0) {
            $this->command->warn("⚠ {$notFound} services not found");
        }
    }
}
