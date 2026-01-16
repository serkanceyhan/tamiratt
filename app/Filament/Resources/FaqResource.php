<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    
    protected static ?string $navigationLabel = 'SSS Yönetimi';
    
    protected static ?string $navigationGroup = 'Yardım ve Destek';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question')
                    ->label('Soru')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                
                Forms\Components\RichEditor::make('answer')
                    ->label('Cevap')
                    ->required()
                    ->columnSpanFull(),
                
                Forms\Components\Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'service' => 'Hizmet',
                        'payment' => 'Ödeme',
                        'technical' => 'Teknik',
                        'general' => 'Genel',
                    ])
                    ->required(),
                
                Forms\Components\TextInput::make('order')
                    ->label('Sıra')
                    ->numeric()
                    ->default(0)
                    ->required(),
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Soru')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategori')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'service' => 'Hizmet',
                        'payment' => 'Ödeme',
                        'technical' => 'Teknik',
                        'general' => 'Genel',
                    })
                    ->colors([
                        'primary' => 'service',
                        'warning' => 'payment',
                        'danger' => 'technical',
                        'secondary' => 'general',
                    ]),
                
                Tables\Columns\TextColumn::make('order')
                    ->label('Sıra')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'service' => 'Hizmet',
                        'payment' => 'Ödeme',
                        'technical' => 'Teknik',
                        'general' => 'Genel',
                    ]),
                
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
            ->reorderable('order')
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
