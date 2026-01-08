<?php

namespace App\Filament\Provider\Pages;

use App\Models\Provider;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?string $title = 'Panel';
    
    protected static ?string $navigationLabel = 'Panel';

    public function getColumns(): int|string|array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Provider\Widgets\BalanceOverview::class,
            \App\Filament\Provider\Widgets\RecentLeads::class,
        ];
    }
}
