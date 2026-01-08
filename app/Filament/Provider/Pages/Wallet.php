<?php

namespace App\Filament\Provider\Pages;

use App\Models\BalanceTransaction;
use App\Models\Package;
use App\Models\Provider;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class Wallet extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Cüzdanım';

    protected static ?string $title = 'Cüzdanım';

    protected static ?string $navigationGroup = 'Finans';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.provider.pages.wallet';

    public ?int $selectedPackageId = null;
    public ?float $customAmount = null;
    public string $paymentMethod = 'credit_card';

    public function mount(): void
    {
        $packages = Package::active()->ordered()->get();
        if ($packages->isNotEmpty()) {
            $this->selectedPackageId = $packages->first()->id;
        }
    }

    public function getProvider(): ?Provider
    {
        $user = Auth::user();
        return $user ? Provider::where('user_id', $user->id)->first() : null;
    }

    public function getBalance(): string
    {
        $provider = $this->getProvider();
        return $provider 
            ? number_format($provider->balance, 2, ',', '.') . ' ₺'
            : '0,00 ₺';
    }

    public function getPackages()
    {
        return Package::active()->ordered()->get();
    }

    public function selectPackage(int $packageId): void
    {
        $this->selectedPackageId = $packageId;
        $this->customAmount = null;
    }

    public function setCustomAmount(): void
    {
        $this->selectedPackageId = null;
    }

    public function getSelectedAmount(): float
    {
        if ($this->selectedPackageId) {
            $package = Package::find($this->selectedPackageId);
            return $package ? $package->price : 0;
        }
        return $this->customAmount ?? 0;
    }

    public function processPayment(): void
    {
        $amount = $this->getSelectedAmount();

        if ($amount < 50) {
            Notification::make()
                ->title('Minimum tutar 50 ₺')
                ->danger()
                ->send();
            return;
        }

        $provider = $this->getProvider();
        if (!$provider) {
            Notification::make()
                ->title('Provider bulunamadı')
                ->danger()
                ->send();
            return;
        }

        // TODO: Integrate with Iyzico/Stripe payment gateway
        // For now, simulate successful payment
        
        $provider->addBalance(
            $amount,
            'Bakiye yükleme - ' . ($this->selectedPackageId ? 'Paket' : 'Özel tutar'),
            $this->selectedPackageId,
            'test_payment_' . time()
        );

        Notification::make()
            ->title('Ödeme Başarılı!')
            ->body($amount . ' ₺ bakiyenize eklendi.')
            ->success()
            ->send();

        $this->redirect(static::getUrl());
    }

    public function table(Table $table): Table
    {
        $provider = $this->getProvider();

        return $table
            ->query(
                BalanceTransaction::query()
                    ->where('provider_id', $provider?->id ?? 0)
                    ->latest()
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('İşlem')
                    ->limit(40),
                TextColumn::make('amount')
                    ->label('Tutar')
                    ->formatStateUsing(function ($state, $record) {
                        $prefix = $record->type === 'credit' ? '+' : '-';
                        $color = $record->type === 'credit' ? 'text-green-600' : 'text-red-600';
                        return "<span class='{$color}'>{$prefix}" . number_format($state, 2, ',', '.') . " ₺</span>";
                    })
                    ->html(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10])
            ->emptyStateHeading('Henüz işlem yok')
            ->emptyStateDescription('Bakiye yüklediğinizde işlemleriniz burada görünecek.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }
}
