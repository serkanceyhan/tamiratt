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
                'hero_image' => 'icons-images/AHŞAP MOBİLYA TAMİRİ/Dolap Kilit ve Ray Tamiri.jpg',
                'icon_svg' => 'icons-images/AHŞAP MOBİLYA TAMİRİ/Dolap Kilit ve Ray Sistemi.svg',
            ],
            'genel-ofis-mobilya-tamiri' => [
                'hero_image' => 'icons-images/AHŞAP MOBİLYA TAMİRİ/Genel Mobilya Tamiri.jpg',
                'icon_svg' => 'icons-images/AHŞAP MOBİLYA TAMİRİ/Genel Mobilya Tamiri.svg',
            ],
            'ofis-mobilya-boya-cila' => [
                'hero_image' => 'icons-images/AHŞAP MOBİLYA TAMİRİ/Mobilya Boya ve Cila.jpg',
                'icon_svg' => 'icons-images/AHŞAP MOBİLYA TAMİRİ/Mobilya Boya ve Cila.svg',
            ],
            'ofis-masasi-tamiri' => [
                'hero_image' => 'icons-images/AHŞAP MOBİLYA TAMİRİ/Ofis Masası Tamiri.jpg',
                'icon_svg' => 'icons-images/AHŞAP MOBİLYA TAMİRİ/Ofis Masası Tamiri.svg',
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
                'hero_image' => 'icons-images/MONTAJ-NAKLİYE/Demontaj.jpg',
                'icon_svg' => 'icons-images/MONTAJ-NAKLİYE/Demontaj.svg',
            ],
            'ofis-mobilya-kurulum-montaj' => [
                'hero_image' => 'icons-images/MONTAJ-NAKLİYE/Montaj.jpg',
                'icon_svg' => 'icons-images/MONTAJ-NAKLİYE/Montaj.svg',
            ],
            'ofis-tasima-nakliye' => [
                'hero_image' => 'icons-images/MONTAJ-NAKLİYE/Ofis Taşıma.jpg',
                'icon_svg' => 'icons-images/MONTAJ-NAKLİYE/Ofis Taşıma.svg',
            ],

            // OFİS KOLTUĞU TAMİRİ
            'amortisor-degisimi' => [
                'hero_image' => 'icons-images/OFİS KOLTUĞU TAMİRİ/Amortisör Değişimi.jpg',
                'icon_svg' => 'icons-images/OFİS KOLTUĞU TAMİRİ/Amortisör Değişimi.svg',
            ],
            'koltuk-ayak-kolcak-degisimi' => [
                'hero_image' => 'icons-images/OFİS KOLTUĞU TAMİRİ/Koltuk Ayak-Kolçak Değişimi.jpg',
                'icon_svg' => 'icons-images/OFİS KOLTUĞU TAMİRİ/Koltuk Ayak-Kolçak Değişimi.svg',
            ],
            'mekanizma-tekerlek-tamiri' => [
                'hero_image' => 'icons-images/OFİS KOLTUĞU TAMİRİ/Mekanizma-Tekerlek Değişimi.jpg',
                'icon_svg' => 'icons-images/OFİS KOLTUĞU TAMİRİ/Mekanizma & Tekerlek Tamiri.svg',
            ],
            'ofis-sandalyesi-tamiri' => [
                'hero_image' => 'icons-images/OFİS KOLTUĞU TAMİRİ/Ofis Sandalyesi Tamiri.jpg',
                'icon_svg' => 'icons-images/OFİS KOLTUĞU TAMİRİ/Ofis Sandalyesi Tamiri.svg',
            ],

            // OFİS MOBİLYA ÜRETİMİ
            'karsilama-bankosu-imalati' => [
                'hero_image' => 'icons-images/OFİS MOBİLYA ÜRETİMİ/Karşılama Bankosu.jpg',
                'icon_svg' => 'icons-images/OFİS MOBİLYA ÜRETİMİ/Karşılama Bankosu.svg',
            ],
            'makam-takimi-imalati' => [
                'hero_image' => 'icons-images/OFİS MOBİLYA ÜRETİMİ/Makam Takımı İmalatı.jpg',
                'icon_svg' => 'icons-images/OFİS MOBİLYA ÜRETİMİ/Makam Takımı İmalatı.svg',
            ],
            'ofis-dolabi-kitaplik-uretimi' => [
                'hero_image' => 'icons-images/OFİS MOBİLYA ÜRETİMİ/Ofis Dolabi Üretimi.jpg',
                'icon_svg' => 'icons-images/OFİS MOBİLYA ÜRETİMİ/Ofis Dolabı Üretimi.svg',
            ],
            'toplanti-masasi-imalati' => [
                'hero_image' => 'icons-images/OFİS MOBİLYA ÜRETİMİ/Toplantı Masası İmalatı.jpg',
                'icon_svg' => 'icons-images/OFİS MOBİLYA ÜRETİMİ/Toplantı Masası İmalatı.svg',
            ],
            'workstation-calisma-masasi' => [
                'hero_image' => 'icons-images/OFİS MOBİLYA ÜRETİMİ/Workstation.jpg',
                'icon_svg' => 'icons-images/OFİS MOBİLYA ÜRETİMİ/Workstation.svg',
            ],

            // YEDEK PARÇA
            'ofis-koltugu-yedek-parca' => [
                'hero_image' => 'icons-images/YEDEK PARÇA/Koltuk Yedek Parçaları.jpg',
                'icon_svg' => 'icons-images/YEDEK PARÇA/Koltuk Yedek Parçaları.svg',
            ],
            'ofis-masa-aksesuarlari' => [
                'hero_image' => 'icons-images/YEDEK PARÇA/Masa Aksesuarları.jpg',
                'icon_svg' => 'icons-images/YEDEK PARÇA/Masa Aksesuarları.svg',
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
