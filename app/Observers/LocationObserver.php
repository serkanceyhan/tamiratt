<?php

namespace App\Observers;

use App\Models\Location;
use App\Http\Controllers\SeoController;
use App\Jobs\GenerateCoverageJob;

class LocationObserver
{
    /**
     * Handle the Location "saved" event.
     */
    public function saved(Location $location): void
    {
        if ($location->isDirty(['slug', 'is_active', 'type'])) {
            SeoController::clearLocationCache();
        }

        // Dispatch coverage job when location becomes active
        if ($location->wasChanged('is_active') && $location->is_active) {
            GenerateCoverageJob::dispatch($location);
        }
    }

    /**
     * Handle the Location "deleted" event.
     */
    public function deleted(Location $location): void
    {
        SeoController::clearLocationCache();
    }
}
