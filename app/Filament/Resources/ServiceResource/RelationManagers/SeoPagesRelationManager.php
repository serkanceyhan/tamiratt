<?php

namespace App\Filament\Resources\ServiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;

class SeoPagesRelationManager extends RelationManager
{
    protected static string $relationship = 'seoPages';
    
    protected static ?string $title = 'Kapsama Alanı';
    
    protected static ?string $label = 'SEO Sayfası';
    
    protected static ?string $pluralLabel = 'SEO Sayfaları';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('location.name')
                    ->label('İlçe')
                    ->disabled(),
                    
                Forms\Components\TextInput::make('slug')
                    ->disabled(),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                    
                Forms\Components\TextInput::make('custom_hero_title')
                    ->label('Özel Başlık')
                    ->helperText('Boş bırakılırsa otomatik oluşturulur'),
                    
                Forms\Components\RichEditor::make('custom_content')
                    ->label('Özel İçerik')
                    ->helperText('Boş bırakılırsa hizmet şablonu kullanılır')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('slug')
            ->defaultGroup('location.parent_id')
            ->groups([
                Tables\Grouping\Group::make('location.parent_id')
                    ->label('İl')
                    ->getTitleFromRecordUsing(fn ($record) => $record->location->parent->name ?? 'Bilinmiyor')
                    ->collapsible(),
            ])
            ->modifyQueryUsing(function ($query) {
                $query->join('locations', 'seo_pages.location_id', '=', 'locations.id')
                    ->join('locations as parent_locations', 'locations.parent_id', '=', 'parent_locations.id')
                    ->select('seo_pages.*')
                    ->orderByRaw("CASE 
                        WHEN parent_locations.name = 'İstanbul' THEN 1
                        WHEN parent_locations.name = 'Ankara' THEN 2
                        WHEN parent_locations.name = 'İzmir' THEN 3
                        ELSE 4
                    END")
                    ->orderBy('parent_locations.name')
                    ->orderBy('locations.name');
            })
            ->defaultPaginationPageOption(500)
            ->paginationPageOptions([100, 250, 500, 'all'])
            ->columns([
                Tables\Columns\TextColumn::make('location.name')
                    ->label('İlçe')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('full_url')
                    ->label('Sayfa URL')
                    ->getStateUsing(fn ($record) => url('/' . $record->slug))
                    ->url(fn ($record) => url('/' . $record->slug))
                    ->openUrlInNewTab()
                    ->copyable()
                    ->limit(50)
                    ->tooltip(fn ($record) => url('/' . $record->slug)),
                    
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('custom_content')
                    ->label('Özel')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !empty($record->custom_content))
                    ->tooltip(fn ($record) => !empty($record->custom_content) ? 'Özelleştirilmiş içerik var' : 'Varsayılan içerik'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city')
                    ->label('İl Filtresi')
                    ->options(
                        Location::where('type', 'city')
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            $query->whereHas('location', function ($q) use ($data) {
                                $q->where('parent_id', $data['value']);
                            });
                        }
                    }),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif Durum')
                    ->placeholder('Tümü')
                    ->trueLabel('Sadece Aktif')
                    ->falseLabel('Sadece Pasif'),
                    
                Tables\Filters\TernaryFilter::make('customized')
                    ->label('Özelleştirilmiş')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('custom_content'),
                        false: fn (Builder $query) => $query->whereNull('custom_content'),
                    ),
            ])
            ->headerActions([
                // Manuel ekleme devre dışı (otomatik oluşturulacak)
            ])
            ->actions([
                Tables\Actions\Action::make('customize')
                    ->label('Özelleştir')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->form([
                        Forms\Components\TextInput::make('custom_hero_title')
                            ->label('Özel Başlık'),
                        Forms\Components\RichEditor::make('custom_content')
                            ->label('Özel İçerik')
                            ->columnSpanFull(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update($data);
                    }),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Toplu Aktif Et')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                        
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Toplu Pasif Et')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                        
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
