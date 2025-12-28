<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    
    protected static ?string $navigationGroup = 'SEO & Kapsama';
    
    protected static ?string $navigationLabel = 'Hizmetler';
    
    protected static ?string $pluralLabel = 'Hizmetler';
    
    protected static ?string $modelLabel = 'Hizmet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Temel Bilgiler')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Textarea::make('short_description')
                            ->label('Kısa Açıklama')
                            ->rows(3)
                            ->maxLength(200)
                            ->helperText('Ana sayfada ve servis detayında gösterilecek kısa açıklama (max 200 karakter)')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('parent_id')
                            ->label('Ana Hizmet')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Ana hizmet seçin (opsiyonel)')
                            ->helperText('Boş bırakılırsa ana hizmet olur'),
                        Forms\Components\RichEditor::make('master_content')
                            ->label('Ana İçerik')
                            ->required()
                            ->helperText('Dinamik konum adı için {location} yer tutucusunu kullanın')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('icon')
                            ->label('Icon')
                            ->options([
                                'chair' => 'Sandalye',
                                'carpenter' => 'Marangoz',
                                'build' => 'İnşaat',
                                'cleaning_services' => 'Temizlik',
                                'handyman' => 'Tamir',
                                'construction' => 'Yapı',
                                'brush' => 'Fırça',
                                'hardware' => 'Donanım',
                                'plumbing' => 'Tesisat',
                                'electrical_services' => 'Elektrik',
                            ])
                            ->searchable()
                            ->placeholder('Icon seçin')
                            ->helperText('Ana sayfada gösterilecek Material Icon'),
                        Forms\Components\Toggle::make('show_on_homepage')
                            ->label('Ana Sayfada Göster')
                            ->helperText('Bu hizmet ana sayfadaki "Hizmetler" bölümünde görünsün mü?')
                            ->default(false),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ]),
                    
                Forms\Components\Section::make('Kapsama Alanı')
                    ->description('Bu hizmetin sunulacağı illeri seçin. Seçilen illerin tüm ilçeleri otomatik olarak dahil edilir.')
                    ->schema([
                        Forms\Components\Select::make('coverage_cities')
                            ->label('İller')
                            ->multiple()
                            ->searchable()
                            ->options(function () {
                                return cache()->remember('cities_for_select', 3600, function () {
                                    return \App\Models\Location::where('type', 'city')
                                        ->orderBy('name')
                                        ->pluck('name', 'id');
                                });
                            })
                            ->helperText('Birden fazla il seçebilirsiniz. Her ilin tüm ilçeleri otomatik dahil edilecektir.')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // Ana hizmetleri ve hemen altlarındaki alt hizmetleri sırala
                return $query
                    ->orderByRaw('COALESCE(parent_id, id)')
                    ->orderBy('parent_id')
                    ->orderBy('name');
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Hizmet Adı')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record) return $state;
                        $indent = $record->parent_id ? '　　↳ ' : '';
                        return $indent . $state;
                    })
                    ->weight(fn($record) => ($record && $record->parent_id) ? 'normal' : 'bold')
                    ->color(fn($record) => ($record && $record->parent_id) ? 'gray' : 'primary'),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Ana Hizmet')
                    ->sortable()
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('children_count')
                    ->label('Alt Hizmetler')
                    ->counts('children')
                    ->suffix(' adet')
                    ->toggleable()
                    ->visible(fn($record) => $record && !$record->parent_id),
                Tables\Columns\TextColumn::make('slug')
                    ->label('URL')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SeoPagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
