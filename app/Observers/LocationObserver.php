<?php

namespace App\Observers;

use App\Models\Location;
use App\Jobs\GenerateCoverageJob;

class LocationObserver
{
    public function updated(Location $location): void
    {
        // is_active değiştiğinde ve true olduğunda tetiklenir
        if ($location->isDirty('is_active') && $location->is_active) {
            // Job'ı dispatch et
            GenerateCoverageJob::dispatch($location);
        }
    }
}
