<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Ofis Koltuğu Tamiri',
                'slug' => 'ofis-koltugu-tamiri',
                'icon' => 'chair', // Material Icon
                'master_content' => '<p>Profesyonel ofis koltuğu tamir hizmetleri. Amortisör, mekanizma, kaplama ve tüm yedek parça değişimleri.</p>',
                'short_description' => 'Ofis koltukları için profesyonel tamir ve bakım hizmetleri',
                'show_on_homepage' => true,
                'is_active' => true,
                'children' => [
                    ['name' => 'Ofis Sandalyesi Tamiri', 'slug' => 'ofis-sandalyesi-tamiri'],
                    ['name' => 'Amortisör Değişimi', 'slug' => 'amortisor-degisimi'],
                    ['name' => 'Mekanizma & Tekerlek Tamiri', 'slug' => 'mekanizma-tekerlek-tamiri'],
                    ['name' => 'Koltuk Ayak & Kolçak Değişimi', 'slug' => 'koltuk-ayak-kolcak-degisimi'],
                ],
            ],
            [
                'name' => 'Döşeme & Kaplama',
                'slug' => 'doseme-kaplama',
                'icon' => 'brush',
                'master_content' => '<p>Ofis mobilyası döşeme ve kaplama hizmetleri. Deri, kumaş ve suni deri seçenekleri ile yenileme.</p>',
                'short_description' => 'Deri, kumaş ve özel kaplama hizmetleri',
                'show_on_homepage' => true,
                'is_active' => true,
                'children' => [
                    ['name' => 'Oyuncu Koltuğu Kaplama', 'slug' => 'oyuncu-koltugu-kaplama'],
                    ['name' => 'Deri Koltuk Yüz Değişimi', 'slug' => 'deri-koltuk-yuz-degisimi'],
                    ['name' => 'Kumaş Kaplama & Yenileme', 'slug' => 'kumas-koltuk-kaplama'],
                    ['name' => 'Puf & Bench Kaplama', 'slug' => 'puf-bench-kaplama'],
                ],
            ],
            [
                'name' => 'Ahşap Mobilya Tamiri',
                'slug' => 'ahsap-mobilya-tamiri',
                'icon' => 'carpenter',
                'master_content' => '<p>Ahşap ofis mobilyaları için tamir, boya, cila ve restorasyon hizmetleri.</p>',
                'short_description' => 'Masa, dolap ve ahşap mobilya tamir hizmetleri',
                'show_on_homepage' => true,
                'is_active' => true,
                'children' => [
                    ['name' => 'Ofis Masası Tamiri', 'slug' => 'ofis-masasi-tamiri'],
                    ['name' => 'Genel Mobilya Tamiri', 'slug' => 'genel-ofis-mobilya-tamiri'],
                    ['name' => 'Dolap Kilit & Ray Tamiri', 'slug' => 'dolap-kilit-ray-tamiri'],
                    ['name' => 'Mobilya Boya & Cila', 'slug' => 'ofis-mobilya-boya-cila'],
                ],
            ],
            [
                'name' => 'Ofis Mobilya Üretimi',
                'slug' => 'ofis-mobilya-uretimi',
                'icon' => 'construction',
                'master_content' => '<p>Özel ölçü ofis mobilyası imalatı. Makam takımı, toplantı masası, workstation ve özel tasarım üretim.</p>',
                'short_description' => 'Özel ölçü mobilya imalatı ve üretim hizmetleri',
                'show_on_homepage' => true,
                'is_active' => true,
                'children' => [
                    ['name' => 'Makam & Yönetici Takımı İmalatı', 'slug' => 'makam-takimi-imalati'],
                    ['name' => 'Toplantı Masası İmalatı', 'slug' => 'toplanti-masasi-imalati'],
                    ['name' => 'Workstation (Çoklu Masa) Üretimi', 'slug' => 'workstation-calisma-masasi'],
                    ['name' => 'Karşılama Bankosu İmalatı', 'slug' => 'karsilama-bankosu-imalati'],
                    ['name' => 'Ofis Dolabı & Kitaplık Üretimi', 'slug' => 'ofis-dolabi-kitaplik-uretimi'],
                ],
            ],
            [
                'name' => 'Montaj & Nakliye',
                'slug' => 'montaj-nakliye',
                'icon' => 'local_shipping',
                'master_content' => '<p>Ofis taşıma, montaj ve demontaj hizmetleri. Profesyonel ekip ile güvenli nakliye.</p>',
                'short_description' => 'Taşıma, kurulum ve montaj hizmetleri',
                'show_on_homepage' => true,
                'is_active' => true,
                'children' => [
                    ['name' => 'Ofis Taşıma & Nakliye', 'slug' => 'ofis-tasima-nakliye'],
                    ['name' => 'Kurulum & Montaj', 'slug' => 'ofis-mobilya-kurulum-montaj'],
                    ['name' => 'Demontaj (Söküm)', 'slug' => 'ofis-mobilya-sokum-demontaj'],
                ],
            ],
            [
                'name' => 'Yedek Parça',
                'slug' => 'yedek-parca',
                'icon' => 'build',
                'master_content' => '<p>Ofis mobilyası yedek parçaları. Orijinal ve muadil parça seçenekleri.</p>',
                'short_description' => 'Ofis mobilyası yedek parça satışı',
                'show_on_homepage' => true,
                'is_active' => true,
                'children' => [
                    ['name' => 'Ofis Koltuğu Yedek Parça', 'slug' => 'ofis-koltugu-yedek-parca'],
                    ['name' => 'Ofis Masa Aksesuarları', 'slug' => 'ofis-masa-aksesuarlari'],
                ],
            ],
        ];

        foreach ($services as $serviceData) {
            $children = $serviceData['children'] ?? [];
            unset($serviceData['children']);

            // Ana hizmeti oluştur
            $parent = Service::create($serviceData);

            // Alt hizmetleri oluştur
            foreach ($children as $childData) {
                Service::create([
                    'name' => $childData['name'],
                    'slug' => $childData['slug'],
                    'parent_id' => $parent->id,
                    'master_content' => "<p>{$childData['name']} hizmeti hakkında detaylı bilgi.</p>",
                    'is_active' => true,
                    'show_on_homepage' => false, // Alt hizmetler ana sayfada gösterilmez
                ]);
            }
        }

        $this->command->info('✅ ' . Service::count() . ' hizmet eklendi (6 ana + ' . (Service::count() - 6) . ' alt hizmet)');
    }
}
