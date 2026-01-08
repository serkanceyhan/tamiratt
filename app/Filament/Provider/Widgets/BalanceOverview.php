<?php

namespace App\Filament\Provider\Widgets;

use App\Models\Provider;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class BalanceOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $provider = $this->getProvider();
        
        if (!$provider) {
            return [
                Stat::make('Durum', 'Hesap Bulunamadı')
                    ->description('Provider profili oluşturulmamış')
                    ->color('danger'),
            ];
        }

        $balance = number_format($provider->balance, 2, ',', '.') . ' ₺';
        $recentCredits = $provider->balanceTransactions()->credits()->sum('amount');
        $recentDebits = $provider->balanceTransactions()->debits()->sum('amount');
        $purchaseCount = $provider->quotePurchases()->count();

        return [
            Stat::make('Mevcut Bakiye', $balance)
                ->description('Kullanılabilir kredi')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "\$dispatch('openWallet')",
                ]),
            
            Stat::make('Açılan Fırsatlar', (string) $purchaseCount)
                ->description('Toplam kilit açılan iş')
                ->descriptionIcon('heroicon-m-key')
                ->color('primary'),
            
            Stat::make('Harcanan', number_format($recentDebits, 0, ',', '.') . ' ₺')
                ->description('Toplam harcama')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning'),
        ];
    }

    protected function getProvider(): ?Provider
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        
        return Provider::where('user_id', $user->id)->first();
    }
}
