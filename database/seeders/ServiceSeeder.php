<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // 1. KATEGORİ: OFİS KOLTUĞU TAMİRİ
            [
                'name' => 'Ofis Koltuğu Tamiri',
                'slug' => 'ofis-koltugu-tamiri',
                'icon' => 'chair',
                'short_description' => 'Ofis koltukları için profesyonel tamir ve bakım hizmetleri',
                'master_content' => '<h2>{location} Ofis Koltuğu Tamiri ve Yedek Parça Hizmeti</h2><p>Ofisinizdeki veya evinizdeki çalışma koltukları zamanla arızalanabilir. <strong>Tamiratt</strong> olarak, <strong>{location}</strong> bölgesindeki işletmelere ve bireysel kullanıcılara profesyonel ofis koltuğu tamir hizmeti sunuyoruz.</p>',
                'show_on_homepage' => true,
                'services' => [
                    [
                        'name' => 'Ofis Sandalyesi Tamiri',
                        'slug' => 'ofis-sandalyesi-tamiri',
                        'icon' => 'chair_alt',
                        'master_hero_title' => '{location} Ofis Sandalyesi Tamiri',
                        'master_content' => '<h2>{location} Ofis Sandalyesi Tamiri ve Bakım Servisi</h2><p>Ofis, çalışma ve sekreter sandalyeleriniz zamanla eski performansını kaybedebilir. <strong>{location}</strong> bölgesinde marka ayırt etmeksizin tüm ofis sandalyelerinin genel bakım ve onarımını yapıyoruz.</p><h3>Genel Bakım Kapsamı</h3><ul><li><strong>Vidalama ve Sıkılaştırma:</strong> Gevşeyen sırt ve oturak vidalarının sıkılması.</li><li><strong>Kırık Parça Onarımı:</strong> Plastik aksamdaki çatlakların tespiti.</li><li><strong>Yağlama:</strong> Hareketli mekanizmaların özel spreylerle yağlanması.</li></ul>'
                    ],
                    [
                        'name' => 'Amortisör Değişimi',
                        'slug' => 'amortisor-degisimi',
                        'icon' => 'height',
                        'master_hero_title' => '{location} Ofis Koltuğu Amortisör Değişimi',
                        'master_content' => '<h2>{location} Ofis Koltuğu Amortisör Değişimi</h2><p>Ofis koltuğunuz oturduğunuzda kendiliğinden aşağı mı iniyor? Bu sorunun kaynağı, koltuğun yükünü taşıyan <strong>hidrolik pistonun (amortisör)</strong> gaz kaçırmasıdır.</p><h3>Hizmet Detayları</h3><ul><li><strong>Class 3 ve Class 4 Piston:</strong> Sertifikalı amortisörler.</li><li><strong>Yerinde Değişim:</strong> 10 dakikada değiştiriyoruz.</li><li><strong>Sessiz Çalışma:</strong> Gıcırtı yapmayan, yumuşak iniş kalkış.</li></ul>'
                    ],
                    [
                        'name' => 'Mekanizma & Tekerlek Tamiri',
                        'slug' => 'mekanizma-tekerlek-tamiri',
                        'icon' => 'settings',
                        'master_hero_title' => '{location} Koltuk Mekanizma ve Tekerlek Tamiri',
                        'master_content' => '<h2>{location} Koltuk Mekanizma Tamiri ve Tekerlek Değişimi</h2><p>Koltuğunuz geriye yaslandığında kilitlenmiyor mu? <strong>{location}</strong> ekibimiz, ofis koltuklarının en kritik parçası olan mekanizma ve tekerlek arızalarını yerinde çözüyor.</p><ul><li><strong>Mekanizma Tamiri:</strong> Kırılan yay sistemi onarımı.</li><li><strong>Sessiz Silikon Tekerlek:</strong> Parke çizmeyen özel tekerlek.</li><li><strong>Yıldız Ayak Değişimi:</strong> Metal ayak montajı.</li></ul>'
                    ],
                    [
                        'name' => 'Koltuk Ayak & Kolçak Değişimi',
                        'slug' => 'koltuk-ayak-kolcak-degisimi',
                        'icon' => 'widgets',
                        'master_hero_title' => '{location} Ofis Koltuğu Ayak ve Kolçak Yedek Parça',
                        'master_content' => '<h2>{location} Ofis Koltuğu Ayak ve Kolçak Değişimi</h2><p>Kırılan plastik ayaklar veya kopan kolçaklar yüzünden koltuğunuzu kullanılamaz hale getirmeyin.</p><ul><li><strong>Metal Yıldız Ayak:</strong> Garantili metal ayak.</li><li><strong>Ayarlı Kolçak:</strong> Yumuşak pedli kolçak.</li></ul>'
                    ]
                ]
            ],
            // 2. DÖŞEME & KAPLAMA
            [
                'name' => 'Döşeme & Kaplama',
                'slug' => 'doseme-kaplama',
                'icon' => 'texture',
                'short_description' => 'Deri, kumaş ve özel kaplama hizmetleri',
                'master_content' => '<h2>{location} Profesyonel Koltuk Döşeme ve Kumaş Yenileme</h2><p>Mobilyalarınızın iskeleti sağlamsa, sadece yüzeyini değiştirerek yepyeni bir görünüm elde edebilirsiniz.</p>',
                'show_on_homepage' => true,
                'services' => [
                    [
                        'name' => 'Oyuncu Koltuğu Kaplama',
                        'slug' => 'oyuncu-koltugu-kaplama',
                        'icon' => 'sports_esports',
                        'master_hero_title' => '{location} Oyuncu Koltuğu Kaplama ve Tamiri',
                        'master_content' => '<h2>{location} Oyuncu Koltuğu Döşeme</h2><p>xDrive, Rampage, Hawk markaların oyuncu koltukları yoğun kullanımda soyulma yapar.</p><ul><li><strong>Terletmez Kumaş:</strong> Uzun oturumlarda terletmeyen kumaş.</li><li><strong>Sünger Takviyesi:</strong> Çöken sünger yenileme.</li><li><strong>Kişiselleştirme:</strong> İstediğiniz renk.</li></ul>'
                    ],
                    [
                        'name' => 'Deri Koltuk Yüz Değişimi',
                        'slug' => 'deri-koltuk-yuz-degisimi',
                        'icon' => 'dry_cleaning',
                        'master_hero_title' => '{location} Deri Ofis Koltuğu Yüz Değişimi',
                        'master_content' => '<h2>{location} Ofis Koltuğu Yüz Değişimi</h2><p>İskeleti sağlam ama yüzeyi yıpranmış koltuklarınızı yeniliyoruz.</p><ul><li><strong>Hakiki ve Suni Deri:</strong> Prestijli deri seçenekleri.</li><li><strong>File Kumaş:</strong> Ergonomik fileli koltuklar.</li></ul>'
                    ],
                    [
                        'name' => 'Kumaş Kaplama & Yenileme',
                        'slug' => 'kumas-koltuk-kaplama',
                        'icon' => 'checkroom',
                        'master_hero_title' => '{location} Ofis Koltuğu Kumaş Değişimi',
                        'master_content' => '<h2>{location} Ofis Koltuğu Kumaş Değişimi</h2><p>Koltuklarınızın süngeri sağlam ama kumaşı mı lekelendi?</p><ul><li><strong>Hijyen:</strong> Eski kumalardan kurtulun.</li><li><strong>Kurumsal Kimlik:</strong> Dekorasyona uygun renk.</li><li><strong>Ekonomi:</strong> %70 tasarruf.</li></ul>'
                    ],
                    [
                        'name' => 'Puf & Bench Kaplama',
                        'slug' => 'puf-bench-kaplama',
                        'icon' => 'weekend',
                        'master_hero_title' => '{location} Puf ve Bekleme Koltuğu Kaplama',
                        'master_content' => '<h2>{location} Puf ve Bekleme Koltuğu Kaplama</h2><p>Lobi ve dinlenme alanlarındaki mobilyalarınızı dayanıklı kumaşlarla kaplıyoruz.</p><ul><li><strong>Lobi Mobilyaları</strong></li><li><strong>Özel Dikim</strong></li></ul>'
                    ]
                ]
            ],
            // 3. AHŞAP MOBİLYA
            [
                'name' => 'Ahşap Mobilya Tamiri',
                'slug' => 'ahsap-mobilya-tamiri',
                'icon' => 'carpenter',
                'short_description' => 'Masa, dolap ve ahşap mobilya tamir hizmetleri',
                'master_content' => '<h2>{location} Ofis Mobilyası Tamiri ve Boya Cila</h2><p>Değerli ahşap mobilyalarınızı atmayın, yenileyelim.</p>',
                'show_on_homepage' => true,
                'services' => [
                    [
                        'name' => 'Ofis Masası Tamiri',
                        'slug' => 'ofis-masasi-tamiri',
                        'icon' => 'desk',
                        'master_hero_title' => '{location} Ofis Masası Tamiri',
                        'master_content' => '<h2>{location} Ofis Masası Tamiri</h2><p>Sallanan masalar ofisinizde kötü görüntü yaratır.</p><ul><li><strong>Kilit ve Menteşe</strong></li><li><strong>Ray Tamiri</strong></li><li><strong>Masa Sabitleme</strong></li></ul>'
                    ],
                    [
                        'name' => 'Genel Mobilya Tamiri',
                        'slug' => 'genel-ofis-mobilya-tamiri',
                        'icon' => 'home_work',
                        'master_hero_title' => '{location} Genel Mobilya Tamir',
                        'master_content' => '<h2>{location} Genel Mobilya Tamir</h2><p>Sehpa, etajer, kürsü tamiri.</p>'
                    ],
                    [
                        'name' => 'Dolap Kilit & Ray Tamiri',
                        'slug' => 'dolap-kilit-ray-tamiri',
                        'icon' => 'lock',
                        'master_hero_title' => '{location} Dolap Kilidi ve Ray Tamiri',
                        'master_content' => '<h2>{location} Dolap Kilit ve Ray</h2><p>Teleskopik ray ve merkezi kilit tamiri.</p>'
                    ],
                    [
                        'name' => 'Mobilya Boya & Cila',
                        'slug' => 'ofis-mobilya-boya-cila',
                        'icon' => 'imagesearch_roller',
                        'master_hero_title' => '{location} Mobilya Boya ve Cila',
                        'master_content' => '<h2>{location} Boya ve Cila</h2><p>Çizik giderme, renk değişimi, lake boya.</p>'
                    ]
                ]
            ],
            // 4. ÜRETİM
            [
                'name' => 'Ofis Mobilya Üretimi',
                'slug' => 'ofis-mobilya-uretimi',
                'icon' => 'precision_manufacturing',
                'short_description' => 'Özel ölçü mobilya imalatı',
                'master_content' => '<h2>{location} Özel Ölçü Ofis Mobilyası İmalatı</h2><p>Standart mobilyalar uymuyor mu? Proje bazlı üretim yapıyoruz.</p>',
                'show_on_homepage' => true,
                'services' => [
                    [
                        'name' => 'Makam Takımı İmalatı',
                        'slug' => 'makam-takimi-imalati',
                        'icon' => 'workspace_premium',
                        'master_hero_title' => '{location} Makam Takımı',
                        'master_content' => '<h2>Makam Takımı</h2><p>Prestijli tasarım masalar.</p>'
                    ],
                    [
                        'name' => 'Toplantı Masası İmalatı',
                        'slug' => 'toplanti-masasi-imalati',
                        'icon' => 'groups',
                        'master_hero_title' => '{location} Toplantı Masası',
                        'master_content' => '<h2>Toplantı Masası</h2><p>Kablo yönetimli masalar.</p>'
                    ],
                    [
                        'name' => 'Workstation Üretimi',
                        'slug' => 'workstation-calisma-masasi',
                        'icon' => 'computer',
                        'master_hero_title' => '{location} Workstation',
                        'master_content' => '<h2>Workstation</h2><p>Çoklu çalışma istasyonu.</p>'
                    ],
                    [
                        'name' => 'Karşılama Bankosu',
                        'slug' => 'karsilama-bankosu-imalati',
                        'icon' => 'support_agent',
                        'master_hero_title' => '{location} Resepsiyon',
                        'master_content' => '<h2>Resepsiyon</h2><p>LED aydınlatmalı bankolar.</p>'
                    ],
                    [
                        'name' => 'Ofis Dolabı Üretimi',
                        'slug' => 'ofis-dolabi-kitaplik-uretimi',
                        'icon' => 'inventory_2',
                        'master_hero_title' => '{location} Dolap',
                        'master_content' => '<h2>Dolap</h2><p>Özel ölçü dolaplar.</p>'
                    ]
                ]
            ],
            // 5. MONTAJ
            [
                'name' => 'Montaj & Nakliye',
                'slug' => 'montaj-nakliye',
                'icon' => 'local_shipping',
                'short_description' => 'Taşıma ve montaj',
                'master_content' => '<p>Ofis taşıma ve montaj.</p>',
                'show_on_homepage' => true,
                'services' => [
                    [
                        'name' => 'Ofis Taşıma',
                        'slug' => 'ofis-tasima-nakliye',
                        'icon' => 'package_2',
                        'master_hero_title' => '{location} Ofis Taşıma',
                        'master_content' => '<h2>Ofis Taşıma</h2><p>Garantili nakliye.</p>'
                    ],
                    [
                        'name' => 'Montaj',
                        'slug' => 'ofis-mobilya-kurulum-montaj',
                        'icon' => 'build',
                        'master_hero_title' => '{location} Montaj',
                        'master_content' => '<h2>Montaj</h2><p>Kurulum hizmeti.</p>'
                    ],
                    [
                        'name' => 'Demontaj',
                        'slug' => 'ofis-mobilya-sokum-demontaj',
                        'icon' => 'manufacturing',
                        'master_hero_title' => '{location} Demontaj',
                        'master_content' => '<h2>Demontaj</h2><p>Söküm hizmeti.</p>'
                    ]
                ]
            ],
            // 6. YEDEK PARÇA
            [
                'name' => 'Yedek Parça',
                'slug' => 'yedek-parca',
                'icon' => 'settings',
                'short_description' => 'Yedek parça satışı',
                'master_content' => '<p>Ofis mobilyası yedek parça.</p>',
                'show_on_homepage' => true,
                'services' => [
                    [
                        'name' => 'Koltuk Yedek Parça',
                        'slug' => 'ofis-koltugu-yedek-parca',
                        'icon' => 'settings_accessibility',
                        'master_hero_title' => '{location} Koltuk Parça',
                        'master_content' => '<h2>Koltuk Parça</h2><p>Amortisör, tekerlek.</p>'
                    ],
                    [
                        'name' => 'Masa Aksesuarları',
                        'slug' => 'ofis-masa-aksesuarlari',
                        'icon' => 'extension',
                        'master_hero_title' => '{location} Masa Aksesuar',
                        'master_content' => '<h2>Masa Aksesuar</h2><p>Priz kutuları.</p>'
                    ]
                ]
            ]
        ];

        foreach ($categories as $catData) {
            $children = $catData['services'] ?? [];
            unset($catData['services']);

            // Ana hizmeti oluştur (parent)
            $parent = Service::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'icon' => $catData['icon'],
                    'short_description' => $catData['short_description'],
                    'master_content' => $catData['master_content'],
                    'show_on_homepage' => $catData['show_on_homepage'],
                    'is_active' => true,
                ]
            );

            // Alt hizmetleri oluştur (children)
            foreach ($children as $serviceData) {
                Service::updateOrCreate(
                    ['slug' => $serviceData['slug']],
                    [
                        'name' => $serviceData['name'],
                        'parent_id' => $parent->id,
                        'icon' => $serviceData['icon'] ?? null,
                        'master_hero_title' => $serviceData['master_hero_title'],
                        'master_content' => $serviceData['master_content'],
                        'is_active' => true,
                        'show_on_homepage' => false,
                    ]
                );
            }
        }

        $this->command->info('✅ ' . Service::count() . ' hizmet eklendi (6 ana + ' . (Service::count() - 6) . ' alt hizmet)');
    }
}
