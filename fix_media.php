<?php

use Spatie\MediaLibrary\MediaCollections\Models\Media;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$m = Media::find(1);
if ($m && $m->disk === 'local') { 
    $oldPath = storage_path('app/' . $m->id);
    $newPath = storage_path('app/public/' . $m->id);
    
    // Check old folder too (sometimes it is stored as id/conversions etc)
    // Actually spatie stores it in a folder named by ID.
    
    if (file_exists($oldPath)) {
        if (!file_exists(dirname($newPath))) mkdir(dirname($newPath), 0777, true);
        
        // Move the whole directory
        rename($oldPath, $newPath);
        
        $m->disk = 'public';
        $m->save();
        echo "Moved and updated media ID {$m->id}.\n";
    } else {
        echo "File not found at {$oldPath}\n";
    } 
} else {
    echo "Media 1 not found or it is already public.\n";
}
