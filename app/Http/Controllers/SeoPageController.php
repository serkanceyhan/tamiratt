<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Location;
use App\Models\SeoPage;
use Illuminate\Http\Request;

class SeoPageController extends Controller
{
    /**
     * SEO sayfası göster - slug'a göre dinamik
     * Slug formatları:
     * - istanbul-fatih-doseme (ilçe + hizmet)
     * - istanbul-doseme (şehir + hizmet)
     * - doseme (sadece hizmet)
     */
    public function show(string $slug)
    {
        // 1. Önce SeoPage tablosunda ara (ilçe-hizmet kombinasyonu)
        $seoPage = SeoPage::where('slug', $slug)
            ->where('is_active', true)
            ->with(['location.parent', 'service'])
            ->first();
        
        if ($seoPage && $seoPage->location && $seoPage->location->is_active) {
            // İlçe sayfası bulundu
            return view('seo.district', ['page' => $seoPage]);
        }
        
        // 2. Slug'ı parçalara ayır (şehir-hizmet veya hizmet)
        $parts = explode('-', $slug);
        $lastPart = end($parts);
        
        // Son kelime hizmet olabilir mi?
        $service = Service::where('slug', $lastPart)
            ->where('is_active', true)
            ->first();
        
        if (!$service) {
            // Tüm slug hizmet olabilir mi?
            $service = Service::where('slug', $slug)
                ->where('is_active', true)
                ->first();
            
            if ($service) {
                // Sadece hizmet sayfası
                return view('seo.service', compact('service'));
            }
            
            abort(404);
        }
        
        // 3. Şehir slug'ını çıkar
        $citySlugParts = array_slice($parts, 0, -1);
        
        if (empty($citySlugParts)) {
            // Sadece hizmet
            return view('seo.service', compact('service'));
        }
        
        $citySlug = implode('-', $citySlugParts);
        
        // Şehir var mı?
        $city = Location::where('slug', $citySlug)
            ->where('type', 'city')
            ->where('is_active', true)
            ->first();
        
        if ($city) {
            // Şehir sayfası
            return view('seo.city', compact('city', 'service'));
        }
        
        // Hiçbir şey bulunamadı
        abort(404);
    }
}
