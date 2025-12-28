<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Service;
use App\Models\SeoPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SeoController extends Controller
{
    /**
     * Display dynamic SEO page
     */
    public function show($slug)
    {
        // Try cache first for performance
        $cacheKey = "seo_page_{$slug}";
        
        $pageData = Cache::remember($cacheKey, 3600, function () use ($slug) {
            return $this->resolvePageData($slug);
        });

        if (!$pageData) {
            abort(404);
        }

        return view('seo.dynamic', $pageData);
    }

    /**
     * Resolve page data from slug
     */
    private function resolvePageData($slug): ?array
    {
        // Parse slug: "kadikoy-ofis-koltugu-tamiri" or "istanbul-ofis-koltugu-tamiri"
        $parts = explode('-', $slug);
        
        // Check if first part is a city
        if ($this->isCitySlug($parts[0])) {
            // City-level page: "istanbul-ofis-koltugu-tamiri"
            $citySlug = $parts[0];
            $serviceSlug = implode('-', array_slice($parts, 1));
            return $this->resolveCityPage([$citySlug, $serviceSlug]);
        }
        
        // District-level page: "kadikoy-ofis-koltugu-tamiri"
        $districtSlug = $parts[0];
        $serviceSlug = implode('-', array_slice($parts, 1));
        return $this->resolveDistrictPage($districtSlug, $serviceSlug);
    }

    /**
     * Resolve city-level page
     */
    private function resolveCityPage(array $parts): ?array
    {
        $citySlug = $parts[0];
        $serviceSlug = implode('-', array_slice($parts, 1));

        $city = Location::where('slug', $citySlug)
            ->where('type', 'city')
            ->where('is_active', true)
            ->first();

        $service = Service::where('slug', $serviceSlug)
            ->where('is_active', true)
            ->first();

        if (!$city || !$service) {
            return null;
        }

        return $this->buildPageData($service, $city, 'city');
    }

    /**
     * Resolve district-level page
     */
    private function resolveDistrictPage(string $districtSlug, string $serviceSlug): ?array
    {
        // Find district in active cities (Istanbul for now)
        $district = Location::where('slug', $districtSlug)
            ->where('type', 'district')
            ->where('is_active', true)
            ->whereHas('parent', fn($q) => $q->where('is_active', true))
            ->with('parent')
            ->first();

        $service = Service::where('slug', $serviceSlug)
            ->where('is_active', true)
            ->first();

        if (!$district || !$service) {
            return null;
        }

        return $this->buildPageData($service, $district, 'district');
    }

    /**
     * Build page data with placeholder replacement
     */
    private function buildPageData(Service $service, Location $location, string $locationType): array
    {
        // Replace {location} placeholder
        $locationName = $location->name;
        $content = str_replace('{location}', $locationName, $service->master_content);
        $heroTitle = str_replace('{location}', $locationName, $service->master_hero_title ?? $service->name);

        // Build breadcrumb
        $breadcrumb = $this->buildBreadcrumb($service, $location, $locationType);

        // Meta description
        $metaDescription = Str::limit(strip_tags($content), 155);

        return [
            'service' => $service,
            'location' => $location,
            'locationType' => $locationType,
            'content' => $content,
            'heroTitle' => $heroTitle,
            'h1' => $heroTitle,
            'breadcrumb' => $breadcrumb,
            'metaTitle' => "{$heroTitle} | Tamiratt",
            'metaDescription' => $metaDescription,
            'canonicalUrl' => url()->current(),
        ];
    }

    /**
     * Build breadcrumb array
     */
    private function buildBreadcrumb(Service $service, Location $location, string $locationType): array
    {
        $breadcrumb = [
            ['name' => 'Ana Sayfa', 'url' => url('/')],
            ['name' => $service->name, 'url' => null], // Service category
        ];

        if ($locationType === 'district' && $location->parent) {
            // Add city for district pages
            $breadcrumb[] = ['name' => $location->parent->name, 'url' => null];
        }

        $breadcrumb[] = ['name' => $location->name, 'url' => null];

        return $breadcrumb;
    }

    /**
     * Check if slug is a city
     */
    private function isCitySlug(string $slug): bool
    {
        return Location::where('slug', $slug)
            ->where('type', 'city')
            ->exists();
    }

    /**
     * Clear SEO page cache (called when service updated)
     */
    public static function clearServiceCache(Service $service)
    {
        // Clear all cached pages for this service
        Cache::flush(); // Simple approach - can be optimized to clear specific keys
    }
}
