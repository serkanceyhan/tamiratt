<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProviderResource\Pages;
use App\Models\Provider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProviderResource extends Resource
{
    protected static ?string $model = Provider::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Hizmet Verenler';

    protected static ?string $modelLabel = 'Hizmet Veren';

    protected static ?string $pluralModelLabel = 'Hizmet Verenler';

    protected static ?string $navigationGroup = 'Pazaryeri';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('verification_status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kullanıcı Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->label('Ad Soyad')
                            ->disabled(),
                        Forms\Components\TextInput::make('user.email')
                            ->label('E-posta')
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefon')
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('Firma Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Firma Adı')
                            ->disabled(),
                        Forms\Components\TextInput::make('tax_number')
                            ->label('Vergi No')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Hizmet Bilgileri')
                    ->schema([
                        Forms\Components\Placeholder::make('service_category_names')
                            ->label('Uzmanlık Alanları')
                            ->content(fn (Provider $record): string => implode(', ', $record->service_category_names)),
                        Forms\Components\Placeholder::make('service_area_names')
                            ->label('Hizmet Bölgeleri')
                            ->content(fn (Provider $record): string => implode(', ', $record->service_area_names)),
                    ])->columns(2),

                Forms\Components\Section::make('Belgeler')
                    ->schema([
                        Forms\Components\Placeholder::make('documents')
                            ->label('Yüklenen Belgeler')
                            ->content(function (Provider $record): string {
                                $media = $record->getMedia('verification_documents');
                                if ($media->isEmpty()) {
                                    return 'Belge yüklenmemiş';
                                }
                                $links = $media->map(fn ($m) => "<a href='{$m->getUrl()}' target='_blank' class='text-primary hover:underline'>{$m->file_name}</a>");
                                return $links->implode('<br>');
                            })->html(),
                    ]),

                Forms\Components\Section::make('Onay Durumu')
                    ->schema([
                        Forms\Components\Select::make('verification_status')
                            ->label('Durum')
                            ->options([
                                'pending' => '⏳ Beklemede',
                                'approved' => '✅ Onaylandı',
                                'rejected' => '❌ Reddedildi',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('verification_notes')
                            ->label('Admin Notları')
                            ->placeholder('Red sebebi veya notlar...')
                            ->rows(3),
                        Forms\Components\TextInput::make('balance')
                            ->label('Bakiye (₺)')
                            ->numeric()
                            ->prefix('₺')
                            ->disabled(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->helperText('Pasif yapıldığında sisteme giriş yapamaz'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Firma')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('verification_status')
                    ->label('Durum')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pending' => 'Beklemede',
                        'approved' => 'Onaylı',
                        'rejected' => 'Reddedildi',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Bakiye')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Başvuru Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('verification_status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'approved' => 'Onaylı',
                        'rejected' => 'Reddedildi',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Onayla')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Provider $record): bool => $record->verification_status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Başvuruyu Onayla')
                    ->modalDescription('Bu hizmet veren onaylanacak ve aktivasyon linki gönderilecek.')
                    ->action(function (Provider $record) {
                        $record->update([
                            'verification_status' => 'approved',
                            'activation_token' => Str::random(64),
                        ]);
                        
                        // TODO: Send activation email/SMS
                        
                        Notification::make()
                            ->title('Başvuru onaylandı')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Reddet')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Provider $record): bool => $record->verification_status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Red Sebebi')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Provider $record, array $data) {
                        $record->update([
                            'verification_status' => 'rejected',
                            'verification_notes' => $data['reason'],
                        ]);
                        
                        Notification::make()
                            ->title('Başvuru reddedildi')
                            ->warning()
                            ->send();
                    }),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProviders::route('/'),
            'view' => Pages\ViewProvider::route('/{record}'),
            'edit' => Pages\EditProvider::route('/{record}/edit'),
        ];
    }
}
