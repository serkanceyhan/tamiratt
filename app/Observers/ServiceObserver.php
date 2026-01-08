<?php

namespace App\Observers;

use App\Models\Service;
use App\Http\Controllers\SeoController;

class ServiceObserver
{
    /**
     * Handle the Service "saved" event.
     */
    public function saved(Service $service): void
    {
        if ($service->isDirty(['slug', 'is_active'])) {
            SeoController::clearServiceCache($service);
        }
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        SeoController::clearServiceCache($service);
    }
}
