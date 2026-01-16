<?php

namespace App\Filament\Customer\Widgets;

use App\Models\ServiceRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LatestRequestWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Son Taleplerim';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ServiceRequest::query()
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Talep No')
                    ->formatStateUsing(fn ($state) => '#' . $state)
                    ->color('primary')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Hizmet')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Konum')
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('warning')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'open' => 'Teklif Bekleniyor',
                        'locked' => 'Devam Ediyor',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal Edildi',
                        'expired' => 'Süresi Doldu',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'info',
                        'locked' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'expired' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('offers_count')
                    ->label('Teklifler')
                    ->counts('offers')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-tag'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Detay')
                    ->icon('heroicon-o-eye')
                    ->url(fn (ServiceRequest $record): string => route('filament.customer.resources.requests.view', $record)),
            ])
            ->paginated(false);
    }
}
