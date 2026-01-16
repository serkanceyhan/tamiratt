<?php

namespace App\Filament\Customer\Pages;

use Filament\Pages\Page;

class Bildirimler extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    
    protected static ?string $navigationLabel = 'Bildirimler';
    
    protected static ?string $title = 'Bildirimler';
    
    protected static ?string $navigationGroup = 'Bildirimler';
    
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.customer.pages.bildirimler';

    public function getViewData(): array
    {
        return [
            'notifications' => auth()->user()->notifications()->latest()->paginate(10),
        ];
    }
}
