<?php

namespace App\Filament\Provider\Resources;

use App\Filament\Provider\Resources\MyOffersResource\Pages;
use App\Models\Provider;
use App\Models\ProviderOffer;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MyOffersResource extends Resource
{
    protected static ?string $model = ProviderOffer::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = 'Tekliflerim';

    protected static ?string $modelLabel = 'Teklif';

    protected static ?string $pluralModelLabel = 'Tekliflerim';

    protected static ?string $navigationGroup = 'İş Yönetimi';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $provider = $user ? Provider::where('user_id', $user->id)->first() : null;

        if (!$provider) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('provider_id', $provider->id)
            ->with(['quote'])
            ->latest();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quote.name')
                    ->label('Müşteri')
                    ->searchable()
                    ->icon('heroicon-m-user'),
                Tables\Columns\TextColumn::make('quote.service_type')
                    ->label('Hizmet')
                    ->badge()
                    ->color('primary')
                    ->limit(25),
                Tables\Columns\TextColumn::make('price')
                    ->label('Teklif Tutarı')
                    ->money('TRY')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('timeline_days')
                    ->label('Süre')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} gün" : '-'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Durum')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                        'gray' => 'withdrawn',
                    ])
                    ->icons([
                        'heroicon-m-clock' => 'pending',
                        'heroicon-m-check-circle' => 'accepted',
                        'heroicon-m-x-circle' => 'rejected',
                        'heroicon-m-arrow-uturn-left' => 'withdrawn',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'Bekliyor',
                        'accepted' => 'Kabul Edildi',
                        'rejected' => 'Reddedildi',
                        'withdrawn' => 'Geri Çekildi',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Bekliyor',
                        'accepted' => 'Kabul Edildi',
                        'rejected' => 'Reddedildi',
                        'withdrawn' => 'Geri Çekildi',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detay'),
                Tables\Actions\Action::make('contact')
                    ->label('İletişime Geç')
                    ->icon('heroicon-m-phone')
                    ->color('success')
                    ->url(fn ($record) => 'tel:' . $record->quote?->email) // Placeholder - should be phone
                    ->visible(fn ($record) => $record->status === 'accepted'),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Henüz teklif göndermediniz')
            ->emptyStateDescription('İş fırsatlarından teklif göndererek başlayın.')
            ->emptyStateIcon('heroicon-o-paper-airplane');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Teklif Detayları')
                    ->schema([
                        Infolists\Components\TextEntry::make('price')
                            ->label('Teklif Tutarı')
                            ->money('TRY'),
                        Infolists\Components\TextEntry::make('timeline_days')
                            ->label('Tahmini Süre')
                            ->formatStateUsing(fn ($state) => $state ? "{$state} gün" : 'Belirtilmedi'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Açıklama')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Notlar')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->notes)),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Müşteri Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('quote.name')
                            ->label('İsim'),
                        Infolists\Components\TextEntry::make('quote.email')
                            ->label('E-posta')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('quote.service_type')
                            ->label('İstenen Hizmet'),
                        Infolists\Components\TextEntry::make('quote.message')
                            ->label('Müşteri Notu')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record->status === 'accepted'),

                Infolists\Components\Section::make('Müşteri Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('contact_locked')
                            ->label('')
                            ->default('🔒 Müşteri teklifi kabul ettiğinde iletişim bilgileri görünecek.')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => $record->status !== 'accepted'),

                Infolists\Components\Section::make('Durum Bilgisi')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->label('Durum')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'pending' => 'warning',
                                'accepted' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match($state) {
                                'pending' => 'Bekliyor',
                                'accepted' => 'Kabul Edildi',
                                'rejected' => 'Reddedildi',
                                'withdrawn' => 'Geri Çekildi',
                                default => $state,
                            }),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Teklif Tarihi')
                            ->dateTime('d M Y, H:i'),
                        Infolists\Components\TextEntry::make('accepted_at')
                            ->label('Kabul Tarihi')
                            ->dateTime('d M Y, H:i')
                            ->visible(fn ($record) => $record->accepted_at),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyOffers::route('/'),
            'view' => Pages\ViewMyOffer::route('/{record}'),
        ];
    }
}
