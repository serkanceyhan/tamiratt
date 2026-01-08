<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FixLocationSlugs extends Command
{
    protected $signature = 'locations:fix-slugs';
    protected $description = 'Regenerate location slugs without IDs';

    public function handle()
    {
        $this->info('Fixing location slugs...');
        
        $locations = Location::all();
        $fixed = 0;
        
        foreach ($locations as $location) {
            // Generate clean slug from name only
            $baseSlug = Str::slug($location->name);
            
            // Check if current slug is different
            if ($location->slug !== $baseSlug) {
                // Check for uniqueness - if same slug exists, add parent city name
                $existing = Location::where('slug', $baseSlug)
                    ->where('id', '!=', $location->id)
                    ->first();
                
                if ($existing) {
                    // Add parent city name to make unique
                    if ($location->parent_id) {
                        $parent = Location::find($location->parent_id);
                        if ($parent) {
                            $baseSlug = Str::slug($location->name . ' ' . $parent->name);
                        }
                    }
                    
                    // If still exists, append first part of name
                    $counter = 1;
                    $uniqueSlug = $baseSlug;
                    while (Location::where('slug', $uniqueSlug)->where('id', '!=', $location->id)->exists()) {
                        $uniqueSlug = $baseSlug . '-' . $counter;
                        $counter++;
                    }
                    $baseSlug = $uniqueSlug;
                }
                
                $oldSlug = $location->slug;
                $location->slug = $baseSlug;
                $location->save();
                
                $this->info("Fixed: {$oldSlug} -> {$baseSlug}");
                $fixed++;
            }
        }
        
        $this->info("Done! Fixed {$fixed} slugs.");
        
        return 0;
    }
}
