<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\RequestResource\Pages;
use App\Filament\Customer\Resources\RequestResource\RelationManagers;
use App\Models\ServiceRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Taleplerim';

    protected static ?string $modelLabel = 'Talep';

    protected static ?string $pluralModelLabel = 'Talepler';

    protected static ?string $navigationGroup = 'Talepler';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->latest();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Talep Detayları')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Açıklama')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('preferred_date')
                            ->label('Tercih Edilen Tarih')
                            ->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Talep No')
                    ->formatStateUsing(fn ($state) => '#' . $state)
                    ->color('primary')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Hizmet')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->iconColor('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Konum')
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('warning')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'open' => 'Teklif Bekleniyor',
                        'locked' => 'Devam Ediyor',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal Edildi',
                        'expired' => 'Süresi Doldu',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'info',
                        'locked' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'expired' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('offers_count')
                    ->label('Teklifler')
                    ->counts('offers')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-tag'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'open' => 'Teklif Bekleniyor',
                        'locked' => 'Devam Ediyor',
                        'completed' => 'Tamamlandı',
                        'cancelled' => 'İptal Edildi',
                        'expired' => 'Süresi Doldu',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detay'),
                Tables\Actions\EditAction::make()
                    ->label('Düzenle')
                    ->visible(fn (ServiceRequest $record): bool => in_array($record->status, ['open', 'draft', 'pending_verification'])),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Henüz talebiniz yok')
            ->emptyStateDescription('İlk talebinizi oluşturarak hizmet almaya başlayın.')
            ->emptyStateIcon('heroicon-o-folder-open')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Yeni Talep Oluştur')
                    ->url('/hizmetler')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OffersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequests::route('/'),
            'view' => Pages\ViewRequest::route('/{record}'),
            'edit' => Pages\EditRequest::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Users create requests via the wizard, not here
    }

    public static function canDelete($record): bool
    {
        return false; // Users can cancel but not delete
    }
}
