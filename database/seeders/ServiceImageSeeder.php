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
        // Service slug => actual filenames mapping
        $serviceImages = [
            'dolap-kilit-ray-tamiri' => [
                'hero_image' => 'services/hero-images/Dolap-Kilit-ve-Ray-Tamiri.jpg',
                'icon_svg' => 'services/icons/Dolap-Kilit-ve-Ray-Sistemi.svg',
            ],
            'genel-ofis-mobilya-tamiri' => [
                'hero_image' => 'services/hero-images/Genel-Mobilya-Tamiri.jpg',
                'icon_svg' => 'services/icons/Genel-Mobilya-Tamiri.svg',
            ],
            'ofis-mobilya-boya-cila' => [
                'hero_image' => 'services/hero-images/Mobilya-Boya-ve-Cila.jpg',
                'icon_svg' => 'services/icons/Mobilya-Boya-ve-Cila.svg',
            ],
            'ofis-masasi-tamiri' => [
                'hero_image' => 'services/hero-images/ofis-masas-tamiri.jpg',
                'icon_svg' => 'services/icons/ofis-masas-tamiri.svg',
            ],
            'deri-koltuk-yuz-degisimi' => [
                'hero_image' => 'services/hero-images/deri-koltuk-yu-z-deg-is-imi.jpg',
                'icon_svg' => 'services/icons/deri-koltuk-yu-z-deg-is-imi.svg',
            ],
            'kumas-koltuk-kaplama' => [
                'hero_image' => 'services/hero-images/kumas-kaplama-ve-yenileme.jpg',
                'icon_svg' => 'services/icons/kumas-kaplama-ve-yenileme.svg',
            ],
            'oyuncu-koltugu-kaplama' => [
                'hero_image' => 'services/hero-images/oyuncu-koltug-u-kaplama.jpg',
                'icon_svg' => 'services/icons/oyuncu-koltug-u-kaplama.svg',
            ],
            'puf-bench-kaplama' => [
                'hero_image' => 'services/hero-images/Puf-ve-Bench-Kaplama.jpg',
                'icon_svg' => 'services/icons/Puf-ve-Bench-Kaplama.svg',
            ],
            'ofis-mobilya-sokum-demontaj' => [
                'hero_image' => 'services/hero-images/Demontaj.jpg',
                'icon_svg' => 'services/icons/Demontaj.svg',
            ],
            'ofis-mobilya-kurulum-montaj' => [
                'hero_image' => 'services/hero-images/Montaj.jpg',
                'icon_svg' => 'services/icons/Montaj.svg',
            ],
            'ofis-tasima-nakliye' => [
                'hero_image' => 'services/hero-images/ofis-tas-ma.jpg',
                'icon_svg' => 'services/icons/ofis-tas-ma.svg',
            ],
            'amortisor-degisimi' => [
                'hero_image' => 'services/hero-images/amortiso-r-deg-is-imi.jpg',
                'icon_svg' => 'services/icons/amortiso-r-deg-is-imi.svg',
            ],
            'koltuk-ayak-kolcak-degisimi' => [
                'hero_image' => 'services/hero-images/koltuk-ayak-kolc-ak-deg-is-imi.jpg',
                'icon_svg' => 'services/icons/koltuk-ayak-kolc-ak-deg-is-imi.svg',
            ],
            'mekanizma-tekerlek-tamiri' => [
                'hero_image' => 'services/hero-images/mekanizma-tekerlek-deg-is-imi.jpg',
                'icon_svg' => 'services/icons/Mekanizma-Tekerlek-Tamiri.svg',
            ],
            'ofis-sandalyesi-tamiri' => [
                'hero_image' => 'services/hero-images/Ofis-Sandalyesi-Tamiri.jpg',
                'icon_svg' => 'services/icons/Ofis-Sandalyesi-Tamiri.svg',
            ],
            'karsilama-bankosu-imalati' => [
                'hero_image' => 'services/hero-images/kars-lama-bankosu.jpg',
                'icon_svg' => 'services/icons/kars-lama-bankosu.svg',
            ],
            'makam-takimi-imalati' => [
                'hero_image' => 'services/hero-images/makam-tak-m-i-malat.jpg',
                'icon_svg' => 'services/icons/makam-tak-m-i-malat.svg',
            ],
            'ofis-dolabi-kitaplik-uretimi' => [
                'hero_image' => 'services/hero-images/ofis-dolabi-u-retimi.jpg',
                'icon_svg' => 'services/icons/ofis-dolab-u-retimi.svg',
            ],
            'toplanti-masasi-imalati' => [
                'hero_image' => 'services/hero-images/toplant-masas-i-malat.jpg',
                'icon_svg' => 'services/icons/toplant-masas-i-malat.svg',
            ],
            'workstation-calisma-masasi' => [
                'hero_image' => 'services/hero-images/Workstation.jpg',
                'icon_svg' => 'services/icons/Workstation.svg',
            ],
            'ofis-koltugu-yedek-parca' => [
                'hero_image' => 'services/hero-images/koltuk-yedek-parc-alar.jpg',
                'icon_svg' => 'services/icons/koltuk-yedek-parc-alar.svg',
            ],
            'ofis-masa-aksesuarlari' => [
                'hero_image' => 'services/hero-images/masa-aksesuarlar.jpg',
                'icon_svg' => 'services/icons/masa-aksesuarlar.svg',
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
