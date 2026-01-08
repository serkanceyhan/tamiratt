<?php

namespace App\Filament\Provider\Resources;

use App\Filament\Provider\Resources\LeadResource\Pages;
use App\Models\Provider;
use App\Models\ServiceRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LeadResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'İşlerim';

    protected static ?string $modelLabel = 'İş Fırsatı';

    protected static ?string $pluralModelLabel = 'İş Fırsatları';

    protected static ?string $navigationGroup = 'İş Yönetimi';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $provider = $user ? Provider::where('user_id', $user->id)->first() : null;

        if (!$provider) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('status', 'open')
            ->when($provider->service_areas, fn ($q) => $q->whereIn('location_id', $provider->service_areas))
            ->when($provider->service_categories, fn ($q) => $q->whereIn('service_id', $provider->service_categories))
            ->with(['service', 'location']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Hizmet')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Konum')
                    ->icon('heroicon-m-map-pin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->description),
                Tables\Columns\TextColumn::make('unlock_cost')
                    ->label('Maliyet')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn () => '10 ₺'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d M, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service_id')
                    ->label('Hizmet')
                    ->relationship('service', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detay'),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('İş Detayları')
                    ->schema([
                        Infolists\Components\TextEntry::make('service.name')
                            ->label('Hizmet'),
                        Infolists\Components\TextEntry::make('location.name')
                            ->label('Konum'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Açıklama')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Talep Tarihi')
                            ->dateTime('d M Y, H:i'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Fotoğraflar')
                    ->schema([
                        Infolists\Components\TextEntry::make('photos_placeholder')
                            ->label('')
                            ->default('Fotoğraflar burada gösterilecek.')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Müşteri Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('contact_name')
                            ->label('İsim')
                            ->placeholder('🔒 Kilidi Aç'),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Telefon')
                            ->placeholder('🔒 Kilidi Aç'),
                    ])
                    ->columns(2)
                    ->description('Müşteri bilgilerini görmek için kilidi açın.'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'view' => Pages\ViewLead::route('/{record}'),
        ];
    }
}
