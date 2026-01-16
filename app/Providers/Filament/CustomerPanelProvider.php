<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CustomerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('customer')
            ->path('hesabim')
            ->login()
            ->brandName('ta\'miratt')
            ->brandLogo(fn () => view('filament.provider.brand-logo'))
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Customer/Resources'), for: 'App\\Filament\\Customer\\Resources')
            ->discoverPages(in: app_path('Filament/Customer/Pages'), for: 'App\\Filament\\Customer\\Pages')
            ->pages([
                \App\Filament\Customer\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Customer/Widgets'), for: 'App\\Filament\\Customer\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->navigationGroups([
                'Talepler',
                'Bildirimler',
                'Ayarlar',
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Ana Sayfa')
                    ->url('/')
                    ->icon('heroicon-o-home'),
                MenuItem::make()
                    ->label('Yeni Talep')
                    ->url('/hizmetler')
                    ->icon('heroicon-o-plus-circle'),
                MenuItem::make()
                    ->label('Hizmet Veren Paneli')
                    ->url('/panel')
                    ->icon('heroicon-o-building-storefront')
                    ->visible(fn () => auth()->user()?->isProvider()),
                MenuItem::make()
                    ->label('Hizmet Veren Ol')
                    ->url('/hizmet-veren/basvuru')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->visible(fn () => !auth()->user()?->isProvider()),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->topNavigation(false);
    }
}
