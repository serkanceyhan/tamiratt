<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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

// SEO Pages - Single route for all formats
Route::get('/{slug}', [App\Http\Controllers\SeoController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('seo.page');


require __DIR__.'/auth.php';
