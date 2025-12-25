<?php

namespace App\Filament\Resources\LocationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DistrictsRelationManager extends RelationManager
{
    protected static string $relationship = 'children';
    
    protected static ?string $title = 'İlçeler';
    
    protected static ?string $modelLabel = 'ilçe';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('İlçe Adı')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_default_coverage')
                    ->label('Varsayılan olarak yeni servislere ekle')
                    ->default(true)
                    ->helperText('Bu ilçe yeni servis oluşturulduğunda otomatik dahil edilsin mi?'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('İlçe Adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_default_coverage')
                    ->label('Varsayılan Kapsama')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_default_coverage')
                    ->label('Varsayılan Kapsama')
                    ->placeholder('Tümü')
                    ->trueLabel('Varsayılanlarda')
                    ->falseLabel('Varsayılanlarda değil'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Durum')
                    ->placeholder('Tümü')
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif'),
            ])
            ->headerActions([
                // İlçeler zaten seeder'dan geldiği için create gerekmez
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('setDefaultTrue')
                        ->label('Varsayılanlara Ekle')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_default_coverage' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('setDefaultFalse')
                        ->label('Varsayılanlardan Çıkar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_default_coverage' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
