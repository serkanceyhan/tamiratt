<?php

namespace App\Filament\Provider\Resources;

use App\Filament\Provider\Resources\WonJobsResource\Pages;
use App\Models\Provider;
use App\Models\ProviderOffer;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WonJobsResource extends Resource
{
    protected static ?string $model = ProviderOffer::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Kazandıklarım';

    protected static ?string $modelLabel = 'Kazanılan İş';

    protected static ?string $pluralModelLabel = 'Kazandıklarım';

    protected static ?string $navigationGroup = 'İş Yönetimi';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $provider = $user ? Provider::where('user_id', $user->id)->first() : null;

        if (!$provider) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        // Only show accepted offers
        return parent::getEloquentQuery()
            ->where('provider_id', $provider->id)
            ->where('status', 'accepted')
            ->with(['quote'])
            ->latest('accepted_at');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quote.name')
                    ->label('Müşteri')
                    ->searchable()
                    ->icon('heroicon-m-user')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('quote.service_type')
                    ->label('Hizmet')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('quote.email')
                    ->label('E-posta')
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Tutar')
                    ->money('TRY')
                    ->weight('bold')
                    ->color('success'),
                Tables\Columns\TextColumn::make('accepted_at')
                    ->label('Kazanma Tarihi')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('contacted')
                    ->label('İletişim')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->getStateUsing(fn () => true), // Placeholder - could track if contacted
            ])
            ->actions([
                Tables\Actions\Action::make('call')
                    ->label('Ara')
                    ->icon('heroicon-m-phone')
                    ->color('success')
                    ->url(fn ($record) => 'tel:' . $record->quote?->email), // Should be phone
                Tables\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(fn ($record) => 'https://wa.me/90' . preg_replace('/[^0-9]/', '', $record->quote?->email ?? ''))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make()
                    ->label('Detay'),
            ])
            ->bulkActions([])
            ->defaultSort('accepted_at', 'desc')
            ->emptyStateHeading('Henüz kazanılan iş yok')
            ->emptyStateDescription('Müşteriler tekliflerinizi kabul ettiğinde burada görünecek.')
            ->emptyStateIcon('heroicon-o-trophy');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Müşteri Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('quote.name')
                            ->label('Müşteri Adı')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('quote.email')
                            ->label('E-posta')
                            ->copyable()
                            ->icon('heroicon-m-envelope'),
                        Infolists\Components\TextEntry::make('phone_placeholder')
                            ->label('Telefon')
                            ->default('Müşteriyi arayabilirsin')
                            ->icon('heroicon-m-phone')
                            ->color('success'),
                    ])
                    ->columns(3)
                    ->description('Müşteri ile iletişime geçebilirsin.'),

                Infolists\Components\Section::make('İş Detayları')
                    ->schema([
                        Infolists\Components\TextEntry::make('quote.service_type')
                            ->label('Hizmet'),
                        Infolists\Components\TextEntry::make('price')
                            ->label('Anlaşılan Tutar')
                            ->money('TRY'),
                        Infolists\Components\TextEntry::make('timeline_days')
                            ->label('Tahmini Süre')
                            ->formatStateUsing(fn ($state) => $state ? "{$state} gün" : 'Belirtilmedi'),
                        Infolists\Components\TextEntry::make('accepted_at')
                            ->label('Kabul Tarihi')
                            ->dateTime('d M Y, H:i'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Müşteri Notu')
                    ->schema([
                        Infolists\Components\TextEntry::make('quote.message')
                            ->label('')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => !empty($record->quote?->message))
                    ->collapsible(),

                Infolists\Components\Section::make('Notlarım')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->label('')
                            ->default('Not eklenmemiş.')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWonJobs::route('/'),
            'view' => Pages\ViewWonJob::route('/{record}'),
        ];
    }
}
