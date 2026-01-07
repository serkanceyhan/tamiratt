<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceRequestResource\Pages;
use App\Models\ServiceRequest;
use App\Models\Service;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationGroup = 'Talepler';
    protected static ?string $navigationLabel = 'Hizmet Talepleri';
    protected static ?string $modelLabel = 'Hizmet Talebi';
    protected static ?string $pluralModelLabel = 'Hizmet Talepleri';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'open')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Talep Bilgileri')
                    ->schema([
                        Forms\Components\Select::make('service_id')
                            ->label('Hizmet')
                            ->options(Service::pluck('name', 'id'))
                            ->disabled(),

                        Forms\Components\Select::make('sub_service_id')
                            ->label('Alt Hizmet')
                            ->options(Service::pluck('name', 'id'))
                            ->disabled(),

                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->disabled(),

                        Forms\Components\KeyValue::make('answers')
                            ->label('Soru Cevapları')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Konum & Zaman')
                    ->schema([
                        Forms\Components\Select::make('location_id')
                            ->label('Konum')
                            ->options(Location::pluck('name', 'id'))
                            ->disabled(),

                        Forms\Components\TextInput::make('address')
                            ->label('Adres')
                            ->disabled(),

                        Forms\Components\DatePicker::make('preferred_date')
                            ->label('Tercih Edilen Tarih')
                            ->disabled(),

                        Forms\Components\TextInput::make('preferred_time')
                            ->label('Tercih Edilen Zaman')
                            ->disabled(),

                        Forms\Components\TextInput::make('urgency')
                            ->label('Aciliyet')
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('İletişim')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon')
                            ->disabled(),

                        Forms\Components\TextInput::make('contact_name')
                            ->label('İsim')
                            ->disabled(),

                        Forms\Components\TextInput::make('email')
                            ->label('E-posta')
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('Durum')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'draft' => 'Taslak',
                                'pending_verification' => 'Doğrulama Bekliyor',
                                'open' => 'Açık',
                                'locked' => 'Kilitli',
                                'completed' => 'Tamamlandı',
                                'cancelled' => 'İptal',
                                'expired' => 'Süresi Doldu',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('lead_price')
                            ->label('Lead Fiyatı')
                            ->numeric()
                            ->prefix('₺'),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Son Geçerlilik'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                Tables\Columns\TextColumn::make('service.name')
                    ->label('Hizmet')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('location.name')
                    ->label('Konum')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('contact_name')
                    ->label('İsim')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Tercih Tarihi')
                    ->date('d.m.Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('urgency')
                    ->label('Aciliyet')
                    ->colors([
                        'secondary' => 'normal',
                        'warning' => 'urgent',
                        'danger' => 'emergency',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'normal' => 'Normal',
                        'urgent' => 'Acil',
                        'emergency' => 'Çok Acil',
                        default => $state,
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Durum')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'pending_verification',
                        'success' => 'open',
                        'primary' => 'locked',
                        'info' => 'completed',
                        'danger' => fn ($state) => in_array($state, ['cancelled', 'expired']),
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'draft' => 'Taslak',
                        'pending_verification' => 'Doğrulama Bekliyor',
                        'open' => 'Açık',
                        'locked' => 'Kilitli',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal',
                        'expired' => 'Süresi Doldu',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('lead_price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'open' => 'Açık',
                        'locked' => 'Kilitli',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal',
                    ]),

                Tables\Filters\SelectFilter::make('urgency')
                    ->label('Aciliyet')
                    ->options([
                        'normal' => 'Normal',
                        'urgent' => 'Acil',
                        'emergency' => 'Çok Acil',
                    ]),

                Tables\Filters\SelectFilter::make('service_id')
                    ->label('Hizmet')
                    ->options(Service::whereNull('parent_id')->pluck('name', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Talep Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('service.name')
                            ->label('Hizmet'),
                        Infolists\Components\TextEntry::make('subService.name')
                            ->label('Alt Hizmet'),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Açıklama')
                            ->columnSpanFull(),
                        Infolists\Components\KeyValueEntry::make('answers')
                            ->label('Soru Cevapları')
                            ->columnSpanFull(),
                    ])->columns(2),

                Infolists\Components\Section::make('Konum & Zaman')
                    ->schema([
                        Infolists\Components\TextEntry::make('location.name')
                            ->label('Konum'),
                        Infolists\Components\TextEntry::make('address')
                            ->label('Adres'),
                        Infolists\Components\TextEntry::make('preferred_date')
                            ->label('Tercih Tarihi')
                            ->date('d.m.Y'),
                        Infolists\Components\TextEntry::make('preferred_time')
                            ->label('Zaman Dilimi')
                            ->formatStateUsing(fn ($state) => match($state) {
                                'morning' => 'Sabah',
                                'afternoon' => 'Öğleden Sonra',
                                'evening' => 'Akşam',
                                'flexible' => 'Esnek',
                                default => $state,
                            }),
                        Infolists\Components\TextEntry::make('urgency')
                            ->label('Aciliyet')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'normal' => 'secondary',
                                'urgent' => 'warning',
                                'emergency' => 'danger',
                                default => 'secondary',
                            }),
                    ])->columns(3),

                Infolists\Components\Section::make('İletişim')
                    ->schema([
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Telefon')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('contact_name')
                            ->label('İsim'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('E-posta')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Kullanıcı'),
                    ])->columns(4),

                Infolists\Components\Section::make('Fotoğraflar')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('media')
                            ->label('')
                            ->schema([
                                Infolists\Components\ImageEntry::make('url')
                                    ->label('')
                                    ->getStateUsing(fn ($record) => $record->getUrl()),
                            ])
                            ->columns(4),
                    ])
                    ->visible(fn ($record) => $record->getMedia('request_photos')->count() > 0),

                Infolists\Components\Section::make('Durum & Fiyatlandırma')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->label('Durum')
                            ->badge(),
                        Infolists\Components\TextEntry::make('lead_price')
                            ->label('Lead Fiyatı')
                            ->money('TRY'),
                        Infolists\Components\TextEntry::make('purchaser.company_name')
                            ->label('Satın Alan'),
                        Infolists\Components\TextEntry::make('purchased_at')
                            ->label('Satın Alma Tarihi')
                            ->dateTime('d.m.Y H:i'),
                        Infolists\Components\TextEntry::make('expires_at')
                            ->label('Son Geçerlilik')
                            ->dateTime('d.m.Y H:i'),
                    ])->columns(5),

                Infolists\Components\Section::make('Teknik Bilgiler')
                    ->schema([
                        Infolists\Components\TextEntry::make('ip_address')
                            ->label('IP Adresi'),
                        Infolists\Components\TextEntry::make('source')
                            ->label('Kaynak'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Oluşturulma')
                            ->dateTime('d.m.Y H:i'),
                    ])->columns(3)
                    ->collapsed(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceRequests::route('/'),
            'view' => Pages\ViewServiceRequest::route('/{record}'),
            'edit' => Pages\EditServiceRequest::route('/{record}/edit'),
        ];
    }
}
