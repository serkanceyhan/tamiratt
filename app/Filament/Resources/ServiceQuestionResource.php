<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceQuestionResource\Pages;
use App\Models\ServiceQuestion;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceQuestionResource extends Resource
{
    protected static ?string $model = ServiceQuestion::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Hizmetler';
    protected static ?string $navigationLabel = 'Talep Soruları';
    protected static ?string $modelLabel = 'Talep Sorusu';
    protected static ?string $pluralModelLabel = 'Talep Soruları';
    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Soru Bilgileri')
                    ->schema([
                        Forms\Components\Select::make('service_id')
                            ->label('Hizmet')
                            ->options(
                                Service::query()
                                    ->select('id', 'name', 'parent_id')
                                    ->get()
                                    ->mapWithKeys(function ($service) {
                                        $prefix = $service->parent_id ? '  └ ' : '';
                                        return [$service->id => $prefix . $service->name];
                                    })
                            )
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('question')
                            ->label('Soru Metni')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Örn: Kaç adet sandalye tamir edilecek?'),

                        Forms\Components\Select::make('type')
                            ->label('Soru Tipi')
                            ->options([
                                'text' => 'Kısa Metin',
                                'textarea' => 'Uzun Metin',
                                'number' => 'Sayı',
                                'select' => 'Tek Seçim (Dropdown)',
                                'radio' => 'Tek Seçim (Radio)',
                                'checkbox' => 'Çoklu Seçim',
                            ])
                            ->default('text')
                            ->required()
                            ->live(),

                        Forms\Components\Repeater::make('options')
                            ->label('Seçenekler')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Seçenek Metni')
                                    ->required(),
                                Forms\Components\TextInput::make('value')
                                    ->label('Değer (Opsiyonel)')
                                    ->placeholder('Boş bırakılırsa metin kullanılır'),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['select', 'radio', 'checkbox'])),
                    ])->columns(2),

                Forms\Components\Section::make('Ayarlar')
                    ->schema([
                        Forms\Components\Toggle::make('is_required')
                            ->label('Zorunlu')
                            ->helperText('Bu soru cevaplanmadan devam edilemesin'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sıralama')
                            ->numeric()
                            ->default(0)
                            ->helperText('Küçük sayılar önce gösterilir'),

                        Forms\Components\Select::make('parent_question_id')
                            ->label('Bağlı Olduğu Soru')
                            ->options(fn () => ServiceQuestion::pluck('question', 'id'))
                            ->searchable()
                            ->nullable()
                            ->helperText('Bu soru başka bir sorunun cevabına bağlı mı?'),
                    ])->columns(2),

                Forms\Components\Section::make('Koşullu Gösterim')
                    ->schema([
                        Forms\Components\KeyValue::make('show_condition')
                            ->label('Gösterim Koşulu')
                            ->keyLabel('Alan')
                            ->valueLabel('Değer')
                            ->helperText('Örn: question_id: 1, value: option_a'),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Hizmet')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('question')
                    ->label('Soru')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tip')
                    ->badge()
                    ->colors([
                        'primary' => 'text',
                        'success' => 'select',
                        'warning' => 'radio',
                        'danger' => 'checkbox',
                    ]),

                Tables\Columns\IconColumn::make('is_required')
                    ->label('Zorunlu')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service_id')
                    ->label('Hizmet')
                    ->options(Service::pluck('name', 'id')),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceQuestions::route('/'),
            'create' => Pages\CreateServiceQuestion::route('/create'),
            'edit' => Pages\EditServiceQuestion::route('/{record}/edit'),
        ];
    }
}
