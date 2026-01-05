<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Service;
use App\Models\Location;

$services = Service::pluck('slug')->toArray();
$istanbul = Location::where('slug', 'istanbul')->where('type', 'city')->first();
$districts = Location::where('parent_id', $istanbul->id)->where('is_active', true)->pluck('slug')->toArray();

$baseUrl = 'https://test.tamiratt.com/';
$urls = [];

// 1. Service-only
foreach ($services as $s) {
    $urls[] = $baseUrl . $s;
}

// 2. City + Service
foreach ($services as $s) {
    $urls[] = $baseUrl . 'istanbul-' . $s;
}

// 3. District + Service
foreach ($districts as $d) {
    foreach ($services as $s) {
        $urls[] = $baseUrl . $d . '-' . $s;
    }
}

file_put_contents('all_dynamic_urls.txt', implode(PHP_EOL, $urls));
echo 'Successfully generated ' . count($urls) . ' URLs.' . PHP_EOL;
