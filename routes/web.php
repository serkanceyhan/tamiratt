<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');


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

// SEO Pages - Single route for all formats
Route::get('/{slug}', [App\Http\Controllers\SeoPageController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('seo.page');

require __DIR__.'/auth.php';
