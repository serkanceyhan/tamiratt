<?php

namespace App\Filament\Customer\Widgets;

use App\Models\ProviderOffer;
use App\Models\ServiceRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $userId = Auth::id();
        $requests = ServiceRequest::where('user_id', $userId)->get();
        
        $openRequests = $requests->where('status', 'open')->count();
        $lockedRequests = $requests->where('status', 'locked')->count();
        $completedRequests = $requests->where('status', 'completed')->count();
        $totalOffers = ProviderOffer::whereIn('service_request_id', $requests->pluck('id'))->count();

        return [
            Stat::make('Açık Talep', $openRequests)
                ->description('Teklif bekleyen')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
            Stat::make('Gelen Teklif', $totalOffers)
                ->description('Toplam teklif')
                ->descriptionIcon('heroicon-m-tag')
                ->color('warning'),
            Stat::make('Devam Eden', $lockedRequests)
                ->description('Aktif işler')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('warning'),
            Stat::make('Tamamlanan', $completedRequests)
                ->description('Başarılı')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
