<?php

namespace App\Filament\Provider\Pages;

use App\Models\BalanceTransaction;
use App\Models\Provider;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Invoices extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Faturalar';

    protected static ?string $title = 'Faturalar';

    protected static ?string $navigationGroup = 'Finans';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.provider.pages.invoices';

    public function getProvider(): ?Provider
    {
        $user = Auth::user();
        return $user ? Provider::where('user_id', $user->id)->first() : null;
    }

    public function table(Table $table): Table
    {
        $provider = $this->getProvider();

        return $table
            ->query(
                BalanceTransaction::query()
                    ->where('provider_id', $provider?->id ?? 0)
                    ->where('type', 'credit') // Only show purchases (credits added)
                    ->latest()
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Dönem')
                    ->formatStateUsing(fn ($state) => $state->translatedFormat('F Y'))
                    ->sortable(),
                TextColumn::make('invoice_number')
                    ->label('Fatura No')
                    ->formatStateUsing(fn ($record) => 'TAM' . date('Y', strtotime($record->created_at)) . str_pad($record->id, 8, '0', STR_PAD_LEFT))
                    ->copyable(),
                TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(40),
                TextColumn::make('amount')
                    ->label('Tutar')
                    ->money('TRY')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('year')
                    ->label('Yıl')
                    ->options(function () {
                        $years = [];
                        for ($y = date('Y'); $y >= 2024; $y--) {
                            $years[$y] = $y;
                        }
                        return $years;
                    })
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->whereYear('created_at', $data['value']);
                        }
                    }),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('download')
                    ->label('İndir')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('primary')
                    ->action(function ($record) {
                        // TODO: Generate and download PDF invoice
                        \Filament\Notifications\Notification::make()
                            ->title('Fatura indirme özelliği yakında')
                            ->body('E-arşiv entegrasyonu tamamlandığında faturalarınızı indirebileceksiniz.')
                            ->info()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Henüz fatura yok')
            ->emptyStateDescription('Bakiye yüklediğinizde faturalarınız burada görünecek.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->defaultSort('created_at', 'desc');
    }
}
