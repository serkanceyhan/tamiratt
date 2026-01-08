<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class CleanDuplicateLocations extends Command
{
    protected $signature = 'locations:clean-duplicates';
    protected $description = 'Remove duplicate location entries, keeping the one with higher ID';

    public function handle()
    {
        $this->info('Finding duplicate locations...');
        
        // Find duplicates by name and parent_id
        $locations = Location::all();
        $grouped = $locations->groupBy(function ($loc) {
            return $loc->name . '-' . ($loc->parent_id ?? 'null');
        });
        
        $duplicatesRemoved = 0;
        
        foreach ($grouped as $key => $group) {
            if ($group->count() > 1) {
                // Sort by ID descending, keep the highest ID (newer entry), delete others
                $sorted = $group->sortByDesc('id');
                $keep = $sorted->first();
                
                $this->info("Duplicate found: {$keep->name} (keeping ID: {$keep->id})");
                
                foreach ($sorted->skip(1) as $duplicate) {
                    $this->warn("  Removing ID: {$duplicate->id}");
                    $duplicate->delete();
                    $duplicatesRemoved++;
                }
            }
        }
        
        $this->info("Done! Removed {$duplicatesRemoved} duplicate locations.");
        
        return 0;
    }
}
