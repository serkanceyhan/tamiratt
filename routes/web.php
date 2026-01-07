<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Services page
Route::get('/hizmetler', function () {
    return view('pages.services');
})->name('services');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Content routes - all types
Route::get('/blog', [App\Http\Controllers\ContentController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\ContentController::class, 'show'])->name('blog.show');
Route::get('/sayfa/{slug}', [App\Http\Controllers\ContentController::class, 'show'])->name('page.show');
Route::get('/sss/{slug}', [App\Http\Controllers\ContentController::class, 'show'])->name('faq.show');
Route::get('/sozlesme/{slug}', [App\Http\Controllers\ContentController::class, 'show'])->name('contract.show');

Route::post('/quote', [App\Http\Controllers\QuoteController::class, 'store'])->name('quote.store');

// Sitemap routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-services.xml', [App\Http\Controllers\SitemapController::class, 'services'])->name('sitemap.services');
Route::get('/sitemap-static.xml', [App\Http\Controllers\SitemapController::class, 'static'])->name('sitemap.static');

// Provider Application Routes
Route::get('/hizmet-veren/basvuru', [App\Http\Controllers\ProviderApplicationController::class, 'create'])->name('provider.apply');
Route::post('/hizmet-veren/basvuru', [App\Http\Controllers\ProviderApplicationController::class, 'store'])->name('provider.apply.store');
Route::get('/hizmet-veren/basvuru/basarili', [App\Http\Controllers\ProviderApplicationController::class, 'success'])->name('provider.apply.success');

// Service Request Success Page (MUST BE BEFORE serviceSlug routes)
Route::get('/talep/basarili/{id}', function ($id) {
    $serviceRequest = App\Models\ServiceRequest::findOrFail($id);
    return view('service-request.success', compact('serviceRequest'));
})->name('service-request.success');

// Service Request Wizard (Dedicated Page)
Route::get('/talep/{serviceSlug}', [App\Http\Controllers\ServiceRequestController::class, 'create'])->name('service-request.create');
Route::get('/talep/{serviceSlug}/{locationSlug?}', [App\Http\Controllers\ServiceRequestController::class, 'create'])->name('service-request.create.location');

// SEO Pages - Single route for all formats (MUST BE LAST - catches all slugs)
Route::get('/{slug}', [App\Http\Controllers\SeoController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('seo.page');


require __DIR__.'/auth.php';
