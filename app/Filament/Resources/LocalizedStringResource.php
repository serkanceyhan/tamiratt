<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocalizedStringResource\Pages;
use App\Models\LocalizedString;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LocalizedStringResource extends Resource
{
    protected static ?string $model = LocalizedString::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';
    
    protected static ?string $navigationGroup = 'Ayarlar';
    
    protected static ?string $navigationLabel = 'Çeviriler';
    
    protected static ?string $pluralLabel = 'Çeviriler';
    
    protected static ?string $modelLabel = 'Çeviri';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Çeviri Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Anahtar')
                            ->required()
                            ->helperText('Örn: home.welcome_message')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('locale')
                            ->label('Dil')
                            ->options([
                                'tr' => 'Türkçe',
                                'en' => 'English',
                            ])
                            ->default('tr')
                            ->required(),
                        Forms\Components\Select::make('group')
                            ->label('Grup')
                            ->options([
                                'home' => 'Ana Sayfa',
                                'nav' => 'Navigasyon',
                                'footer' => 'Alt Bilgi',
                                'contact' => 'İletişim',
                                'services' => 'Hizmetler',
                                'seo' => 'SEO',
                                'forms' => 'Formlar',
                                'auth' => 'Kimlik Doğrulama',
                                'general' => 'Genel',
                            ])
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('label')
                                    ->required(),
                            ]),
                        Forms\Components\Textarea::make('value')
                            ->label('Değer')
                            ->required()
                            ->rows(3)
                            ->helperText('Placeholder için {variable} kullanın'),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->helperText('Admin için not (opsiyonel)')
                            ->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Anahtar')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('locale')
                    ->label('Dil')
                    ->badge()
                    ->colors([
                        'primary' => 'tr',
                        'success' => 'en',
                    ]),
                Tables\Columns\TextColumn::make('group')
                    ->label('Grup')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Değer')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncelleme')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('locale')
                    ->label('Dil')
                    ->options([
                        'tr' => 'Türkçe',
                        'en' => 'English',
                    ]),
                Tables\Filters\SelectFilter::make('group')
                    ->label('Grup')
                    ->options([
                        'home' => 'Ana Sayfa',
                        'nav' => 'Navigasyon',
                        'footer' => 'Alt Bilgi',
                        'contact' => 'İletişim',
                        'services' => 'Hizmetler',
                        'seo' => 'SEO',
                        'forms' => 'Formlar',
                        'auth' => 'Kimlik Doğrulama',
                        'general' => 'Genel',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('key');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocalizedStrings::route('/'),
            'create' => Pages\CreateLocalizedString::route('/create'),
            'edit' => Pages\EditLocalizedString::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
