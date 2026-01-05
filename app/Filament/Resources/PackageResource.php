<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Kredi Paketleri';

    protected static ?string $modelLabel = 'Kredi Paketi';

    protected static ?string $pluralModelLabel = 'Kredi Paketleri';

    protected static ?string $navigationGroup = 'Pazaryeri';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Paket Adı')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Örn: Başlangıç Paketi'),
                        Forms\Components\TextInput::make('credits')
                            ->label('Kredi Miktarı')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->suffix('kredi'),
                        Forms\Components\TextInput::make('price')
                            ->label('Fiyat')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('₺')
                            ->suffix('TL'),
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(3)
                            ->placeholder('Paketi tanımlayın...'),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Paket Adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('credits')
                    ->label('Kredi')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_credit')
                    ->label('Kredi Başı')
                    ->getStateUsing(fn (Package $record): string => number_format($record->price_per_credit, 2) . ' ₺'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
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
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
