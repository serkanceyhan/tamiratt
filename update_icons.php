<?php

use App\Models\Service;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$icons = [
    'ofis-koltugu-tamiri' => 'chair',
    'ofis-sandalyesi-tamiri' => 'chair_alt',
    'amortisor-degisimi' => 'height',
    'mekanizma-tekerlek-tamiri' => 'settings',
    'koltuk-ayak-kolcak-degisimi' => 'widgets',
    'doseme-kaplama' => 'texture',
    'oyuncu-koltugu-kaplama' => 'sports_esports',
    'deri-koltuk-yuz-degisimi' => 'dry_cleaning',
    'kumas-koltuk-kaplama' => 'checkroom',
    'puf-bench-kaplama' => 'weekend',
    'ahsap-mobilya-tamiri' => 'carpenter',
    'ofis-masasi-tamiri' => 'desk',
    'genel-ofis-mobilya-tamiri' => 'home_work',
    'dolap-kilit-ray-tamiri' => 'lock',
    'ofis-mobilya-boya-cila' => 'imagesearch_roller',
    'ofis-mobilya-uretimi' => 'precision_manufacturing',
    'makam-takimi-imalati' => 'workspace_premium',
    'toplanti-masasi-imalati' => 'groups',
    'workstation-calisma-masasi' => 'computer',
    'karsilama-bankosu-imalati' => 'support_agent',
    'ofis-dolabi-kitaplik-uretimi' => 'inventory_2',
    'montaj-nakliye' => 'local_shipping',
    'ofis-tasima-nakliye' => 'package_2',
    'ofis-mobilya-kurulum-montaj' => 'build',
    'ofis-mobilya-sokum-demontaj' => 'manufacturing',
    'yedek-parca' => 'settings',
    'ofis-koltugu-yedek-parca' => 'settings_accessibility',
    'ofis-masa-aksesuarlari' => 'extension',
];

$updated = 0;
foreach ($icons as $slug => $icon) {
    $result = Service::where('slug', $slug)->update(['icon' => $icon]);
    $updated += $result;
}

echo "Updated $updated services with new icons\n";
