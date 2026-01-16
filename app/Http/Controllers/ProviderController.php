<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display the provider's public profile.
     */
    public function show($slug)
    {
        // Slug'dan ID'yi çıkar (son "-" sonrası ID)
        $parts = explode('-', $slug);
        $id = (int) end($parts);
        
        // Provider'ı bul
        $provider = Provider::with(['user', 'media'])
            ->where('id', $id)
            ->where('is_active', true)
            ->where('verification_status', Provider::STATUS_APPROVED)
            ->firstOrFail();
        
        // Doğru slug oluştur (company-name-id formatında)
        $correctSlug = \Illuminate\Support\Str::slug($provider->company_name) . '-' . $provider->id;
        
        // Eğer gelen slug yanlışsa canonical URL'e yönlendir
        if ($slug !== $correctSlug) {
            return redirect()->route('provider.show', ['slug' => $correctSlug], 301);
        }
        
        return view('provider.show', compact('provider'));
    }
}
