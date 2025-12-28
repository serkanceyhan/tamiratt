<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Master sitemap index
     */
    public function index()
    {
        return response()->view('sitemap.index')
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Service-location combination sitemap (Istanbul only for now)
     */
    public function services()
    {
        $urls = Cache::remember('sitemap_services', 86400, function () {
            return $this->generateServiceUrls();
        });

        return response()->view('sitemap.services', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Static pages sitemap
     */
    public function static()
    {
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => url('/hakkimizda'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => url('/iletisim'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => url('/hizmetler'), 'priority' => '0.9', 'changefreq' => 'weekly'],
        ];

        return response()->view('sitemap.static', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate service-location URLs
     */
    private function generateServiceUrls(): array
    {
        $urls = [];
        $services = Service::where('is_active', true)->get();

        // Get active cities (currently only Istanbul)
        $activeCities = Location::where('type', 'city')
            ->where('is_active', true)
            ->get();

        foreach ($activeCities as $city) {
            // City-level pages (e.g., /istanbul-ofis-koltugu-tamiri)
            foreach ($services as $service) {
                $urls[] = [
                    'loc' => url("/{$city->slug}-{$service->slug}"),
                    'lastmod' => $service->updated_at->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.9',
                ];
            }

            // District-level pages (SHORT FORMAT - no city prefix for unique districts)
            $districts = Location::where('type', 'district')
                ->where('parent_id', $city->id)
                ->where('is_active', true)
                ->get();

            foreach ($districts as $district) {
                foreach ($services as $service) {
                    // Use short format: /kadikoy-ofis-koltugu-tamiri (no istanbul prefix)
                    $urls[] = [
                        'loc' => url("/{$district->slug}-{$service->slug}"),
                        'lastmod' => $service->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority' => '0.8',
                    ];
                }
            }
        }

        return $urls;
    }

    /**
     * Clear sitemap cache
     */
    public function clearCache()
    {
        Cache::forget('sitemap_services');
        return response()->json(['message' => 'Sitemap cache cleared']);
    }

    /**
     * Ping Google about sitemap update
     */
    public function pingGoogle()
    {
        $sitemapUrl = url('/sitemap.xml');
        
        try {
            \Illuminate\Support\Facades\Http::get("https://www.google.com/ping?sitemap={$sitemapUrl}");
            return response()->json(['message' => 'Google pinged successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
