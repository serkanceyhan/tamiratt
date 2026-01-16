<?php

namespace App\Filament\Customer\Resources\RequestResource\RelationManagers;

use App\Models\ProviderOffer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class OffersRelationManager extends RelationManager
{
    protected static string $relationship = 'offers';

    protected static ?string $title = 'Gelen Teklifler';

    protected static ?string $icon = 'heroicon-o-tag';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('provider.company_name')
                    ->label('Hizmet Veren')
                    ->weight('bold')
                    ->icon('heroicon-o-building-office'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('TRY')
                    ->color('success')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('message')
                    ->label('Mesaj')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->message),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pending' => 'Bekliyor',
                        'accepted' => 'Kabul Edildi',
                        'rejected' => 'Reddedildi',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('accept')
                    ->label('Kabul Et')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Teklifi Kabul Et')
                    ->modalDescription('Bu teklifi kabul ettiğinizde diğer teklifler reddedilecek ve hizmet veren ile iletişime geçebileceksiniz.')
                    ->visible(fn (ProviderOffer $record): bool => $record->status === 'pending' && $this->ownerRecord->status === 'open')
                    ->action(function (ProviderOffer $record) {
                        // Accept this offer
                        $record->update(['status' => 'accepted']);
                        
                        // Reject other offers
                        $this->ownerRecord->offers()
                            ->where('id', '!=', $record->id)
                            ->update(['status' => 'rejected']);
                        
                        // Lock the request
                        $this->ownerRecord->update(['status' => 'locked']);
                        
                        Notification::make()
                            ->title('Teklif kabul edildi!')
                            ->body('Hizmet veren ile iletişime geçebilirsiniz.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('view_provider')
                    ->label('Profil')
                    ->icon('heroicon-o-user')
                    ->color('gray')
                    ->url(fn (ProviderOffer $record): string => route('provider.show', [
                        'slug' => \Illuminate\Support\Str::slug($record->provider->company_name) . '-' . $record->provider->id
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Henüz teklif yok')
            ->emptyStateDescription('Hizmet sağlayıcılar talebinizi değerlendiriyor.')
            ->emptyStateIcon('heroicon-o-clock');
    }
}
