<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Location;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    /**
     * Show the service request wizard page
     */
    public function create(string $serviceSlug, ?string $locationSlug = null)
    {
        // Find service by slug
        $service = Service::where('slug', $serviceSlug)
            ->where('is_active', true)
            ->first();

        if (!$service) {
            abort(404, 'Hizmet bulunamadı');
        }

        // Find location if provided
        $location = null;
        if ($locationSlug) {
            $location = Location::where('slug', $locationSlug)
                ->where('is_active', true)
                ->first();
        }

        return view('service-request.create', [
            'service' => $service,
            'location' => $location,
        ]);
    }
}
