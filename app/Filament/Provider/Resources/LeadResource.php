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
use Illuminate\Support\Str;

class LeadResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'İşlerim';

    protected static ?string $modelLabel = 'İş Fırsatı';

    protected static ?string $pluralModelLabel = 'İş Fırsatları';

    protected static ?string $navigationGroup = 'İş Yönetimi';

    protected static ?int $navigationSort = 1;

    /**
     * Get current provider
     */
    protected static function getProvider(): ?Provider
    {
        $user = Auth::user();
        return $user ? Provider::where('user_id', $user->id)->first() : null;
    }

    /**
     * Check if lead is unlocked by current provider (has existing offer)
     */
    protected static function isUnlocked($record): bool
    {
        $provider = static::getProvider();
        if (!$provider) return false;
        
        return \App\Models\ProviderOffer::where('provider_id', $provider->id)
            ->where('service_request_id', $record->id)
            ->exists();
    }

    /**
     * Mask customer name (e.g., "Ahmet Yılmaz" -> "Ahmet Y.")
     */
    protected static function maskName(?string $name): string
    {
        if (!$name) return 'Müşteri';
        
        $parts = explode(' ', trim($name));
        if (count($parts) === 1) {
            return Str::limit($parts[0], 8, '.');
        }
        
        $firstName = $parts[0];
        $lastInitial = mb_substr(end($parts), 0, 1) . '.';
        
        return $firstName . ' ' . $lastInitial;
    }

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
                // Smart customer name: full if unlocked, masked if locked
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Müşteri')
                    ->getStateUsing(function ($record) {
                        // Show full name if provider has submitted offer
                        if (static::isUnlocked($record)) {
                            return $record->contact_name;
                        }
                        // Otherwise show masked name
                        return static::maskName($record->contact_name);
                    })
                    ->icon(fn ($record) => static::isUnlocked($record) ? 'heroicon-m-lock-open' : 'heroicon-m-lock-closed')
                    ->iconColor(fn ($record) => static::isUnlocked($record) ? 'success' : 'gray')
                    ->weight('medium')
                    ->searchable(query: function ($query, $search) {
                        // Allow searching by contact name
                        return $query->where('contact_name', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Hizmet')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                // Offer count with competition-based color coding
                Tables\Columns\TextColumn::make('offers_count')
                    ->label('Teklifler')
                    ->counts('offers')
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state === 0 => 'gray',
                        $state <= 3 => 'success',
                        $state <= 6 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => $state . ' Teklif')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Konum')
                    ->icon('heroicon-m-map-pin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(35)
                    ->tooltip(fn ($record) => $record->description),
                // Urgency tag for recent leads
                Tables\Columns\TextColumn::make('urgency_tag')
                    ->label('Durum')
                    ->badge()
                    ->color('danger')
                    ->getStateUsing(function ($record) {
                        // Show "Acil" badge if created within last 6 hours
                        if ($record->created_at->diffInHours(now()) < 6) {
                            return '🔥 Acil';
                        }
                        return null;
                    })
                    ->visible(fn ($record) => $record->created_at->diffInHours(now()) < 6),
                Tables\Columns\TextColumn::make('lead_price')
                    ->label('Teklif Ücreti')
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => number_format($state ?? 10, 2, ',', '.') . ' ₺'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d M')
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
            ->poll('30s')
            ->emptyStateHeading('Henüz iş fırsatı yok')
            ->emptyStateDescription('Bölgenize ve hizmetlerinize uygun talepler geldiğinde burada listelenecek.')
            ->emptyStateIcon('heroicon-o-briefcase');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Header with masked name and avatar
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\Group::make([
                                Infolists\Components\TextEntry::make('masked_customer_name')
                                    ->label('')
                                    ->getStateUsing(fn ($record) => static::maskName($record->contact_name))
                                    ->size('lg')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('service.name')
                                    ->label('')
                                    ->badge()
                                    ->color('primary'),
                            ]),
                            Infolists\Components\Group::make([
                                Infolists\Components\TextEntry::make('lead_price')
                                    ->label('Teklif Ücreti')
                                    ->formatStateUsing(fn ($state) => number_format($state ?? 10, 2, ',', '.') . ' ₺')
                                    ->badge()
                                    ->color('warning'),
                            ]),
                        ]),
                    ])
                    ->columnSpanFull(),

                Infolists\Components\Section::make('İş Detayları')
                    ->schema([
                        Infolists\Components\TextEntry::make('location.name')
                            ->label('Konum')
                            ->icon('heroicon-m-map-pin'),
                        Infolists\Components\TextEntry::make('preferred_date')
                            ->label('Tercih Edilen Tarih')
                            ->date('d M Y')
                            ->icon('heroicon-m-calendar'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Talep Tarihi')
                            ->dateTime('d M Y, H:i'),
                        Infolists\Components\TextEntry::make('id')
                            ->label('Talep Numarası')
                            ->formatStateUsing(fn ($state) => str_pad($state, 8, '0', STR_PAD_LEFT)),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('İşin Detayları')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->label('')
                            ->columnSpanFull(),
                    ]),

                // Contact info - ONLY visible if unlocked
                Infolists\Components\Section::make('Müşteri İletişim Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('contact_name')
                            ->label('İsim')
                            ->icon('heroicon-m-user'),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Telefon')
                            ->icon('heroicon-m-phone')
                            ->copyable()
                            ->url(fn ($record) => 'tel:+90' . $record->phone),
                        Infolists\Components\TextEntry::make('email')
                            ->label('E-posta')
                            ->icon('heroicon-m-envelope')
                            ->copyable()
                            ->visible(fn ($record) => !empty($record->email)),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => static::isUnlocked($record))
                    ->description('Müşteriyle iletişime geçebilirsiniz.'),

                // Locked message - ONLY visible if NOT unlocked
                Infolists\Components\Section::make('Müşteri Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('locked_message')
                            ->label('')
                            ->default('🔒 Teklif vererek müşteri iletişim bilgilerini görebilirsiniz.')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => !static::isUnlocked($record)),

                Infolists\Components\Section::make('Fotoğraflar')
                    ->schema([
                        Infolists\Components\TextEntry::make('photos_count')
                            ->label('')
                            ->getStateUsing(fn ($record) => $record->getMedia('request_photos')->count() > 0 
                                ? $record->getMedia('request_photos')->count() . ' fotoğraf mevcut' 
                                : 'Fotoğraf eklenmemiş')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
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

