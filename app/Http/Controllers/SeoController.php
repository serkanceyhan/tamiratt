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
        // 1. Check if the slug matches a service exactly (using cache to avoid DB query)
        $serviceSlugs = $this->getCachedServiceSlugs();

        if (in_array($slug, $serviceSlugs)) {
            $service = Service::where('slug', $slug)
                ->where('is_active', true)
                ->first();

            if ($service) {
                return $this->buildServiceOnlyPage($service);
            }
        }
        
        // Parse slug: "kadikoy-ofis-koltugu-tamiri" or "istanbul-ofis-koltugu-tamiri"
        $parts = explode('-', $slug);
        
        // 2. Check if first part is a city (using cache)
        if ($this->isCitySlug($parts[0])) {
            // City-level page: "istanbul-ofis-koltugu-tamiri"
            $citySlug = $parts[0];
            $serviceSlug = implode('-', array_slice($parts, 1));

            // Optimization: Early exit if service slug is invalid
            if (!$this->isValidServiceSlug($serviceSlug)) {
                return null;
            }

            return $this->resolveCityPage([$citySlug, $serviceSlug]);
        }
        
        // District-level page: "kadikoy-ofis-koltugu-tamiri"
        $districtSlug = $parts[0];
        $serviceSlug = implode('-', array_slice($parts, 1));

        // Optimization: Early exit if service slug is invalid
        if (!$this->isValidServiceSlug($serviceSlug)) {
            return null;
        }

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
            'metaTitle' => "{$location->name} {$service->name}",
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
     * Check if slug is a city using cache
     */
    private function isCitySlug(string $slug): bool
    {
        $cities = $this->getCachedCitySlugs();
        return in_array($slug, $cities);
    }

    /**
     * Check if slug is a valid service using cache
     */
    private function isValidServiceSlug(string $slug): bool
    {
        $services = $this->getCachedServiceSlugs();
        return in_array($slug, $services);
    }

    /**
     * Build service-only page (no location)
     */
    private function buildServiceOnlyPage(Service $service): array
    {
        $metaDescription = Str::limit(strip_tags($service->master_content ?? ''), 155);
        
        return [
            'service' => $service,
            'location' => null,
            'locationType' => 'service',
            'content' => $service->master_content,
            'heroTitle' => $service->name,
            'h1' => $service->name,
            'breadcrumb' => [
                ['name' => 'Ana Sayfa', 'url' => url('/')],
                ['name' => $service->name, 'url' => null],
            ],
            'metaTitle' => "{$service->name}",
            'metaDescription' => $metaDescription,
            'canonicalUrl' => url()->current(),
        ];
    }

    /**
     * Clear SEO page cache (called when service updated)
     */
    public static function clearServiceCache(Service $service)
    {
        // Clear the service slugs lookup cache
        Cache::forget('active_service_slugs');

        // Clear specific SEO page caches for this service
        // Clear the service-only page
        Cache::forget("seo_page_{$service->slug}");
        
        // Clear city+service combination pages
        $citySlugs = Cache::get('active_city_slugs', []);
        foreach ($citySlugs as $citySlug) {
            Cache::forget("seo_page_{$citySlug}-{$service->slug}");
        }

        // Clear district+service pages (districts from active cities)
        $districts = Location::where('type', 'district')
            ->where('is_active', true)
            ->pluck('slug');
        foreach ($districts as $districtSlug) {
            Cache::forget("seo_page_{$districtSlug}-{$service->slug}");
        }
    }

    /**
     * Clear Location cache
     */
    public static function clearLocationCache()
    {
        // Clear the city slugs lookup cache
        Cache::forget('active_city_slugs');

        // Clear all SEO pages that include location data
        // Get all services to build cache keys
        $serviceSlugs = Cache::get('active_service_slugs', []);
        if (empty($serviceSlugs)) {
            $serviceSlugs = Service::where('is_active', true)->pluck('slug')->toArray();
        }

        // Clear city+service pages
        $cities = Location::where('type', 'city')->where('is_active', true)->pluck('slug');
        foreach ($cities as $citySlug) {
            foreach ($serviceSlugs as $serviceSlug) {
                Cache::forget("seo_page_{$citySlug}-{$serviceSlug}");
            }
        }

        // Clear district+service pages
        $districts = Location::where('type', 'district')->where('is_active', true)->pluck('slug');
        foreach ($districts as $districtSlug) {
            foreach ($serviceSlugs as $serviceSlug) {
                Cache::forget("seo_page_{$districtSlug}-{$serviceSlug}");
            }
        }
    }

    /**
     * Get cached list of active city slugs
     */
    private function getCachedCitySlugs(): array
    {
        return Cache::remember('active_city_slugs', 3600, function () {
            return Location::where('type', 'city')
                ->where('is_active', true)
                ->pluck('slug')
                ->toArray();
        });
    }

    /**
     * Get cached list of active service slugs
     */
    private function getCachedServiceSlugs(): array
    {
        return Cache::remember('active_service_slugs', 3600, function () {
            return Service::where('is_active', true)
                ->pluck('slug')
                ->toArray();
        });
    }
}
