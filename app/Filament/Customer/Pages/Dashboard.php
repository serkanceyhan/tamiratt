<?php

namespace App\Filament\Customer\Pages;

use App\Models\ServiceRequest;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

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
            \App\Filament\Customer\Widgets\StatsOverviewWidget::class,
            \App\Filament\Customer\Widgets\LatestRequestWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }
}
