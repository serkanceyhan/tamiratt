<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update all Istanbul district slugs from "istanbul-district" to "district"
        // Find Istanbul's ID first
        $istanbul = DB::table('locations')
            ->where('slug', 'istanbul')
            ->where('type', 'city')
            ->first();

        if ($istanbul) {
            // Get all istanbul districts
            $districts = DB::table('locations')
                ->where('parent_id', $istanbul->id)
                ->where('type', 'district')
                ->get();

            foreach ($districts as $district) {
                // Remove "istanbul-" prefix
                $newSlug = str_replace('istanbul-', '', $district->slug);
                
                DB::table('locations')
                    ->where('id', $district->id)
                    ->update(['slug' => $newSlug]);
            }
        }
    }

    public function down(): void
    {
        // Revert back to "istanbul-district" format
        $istanbul = DB::table('locations')
            ->where('slug', 'istanbul')
            ->where('type', 'city')
            ->first();

        if ($istanbul) {
            $districts = DB::table('locations')
                ->where('parent_id', $istanbul->id)
                ->where('type', 'district')
                ->get();

            foreach ($districts as $district) {
                // Add "istanbul-" prefix back
                if (!str_starts_with($district->slug, 'istanbul-')) {
                    $newSlug = 'istanbul-' . $district->slug;
                    
                    DB::table('locations')
                        ->where('id', $district->id)
                        ->update(['slug' => $newSlug]);
                }
            }
        }
    }
};
