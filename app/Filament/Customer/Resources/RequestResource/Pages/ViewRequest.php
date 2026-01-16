<?php

namespace App\Filament\Customer\Resources\RequestResource\Pages;

use App\Filament\Customer\Resources\RequestResource;
use App\Models\ServiceRequest;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewRequest extends ViewRecord
{
    protected static string $resource = RequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn (): bool => in_array($this->record->status, ['open', 'draft', 'pending_verification'])),
            Actions\Action::make('cancel')
                ->label('İptal Et')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->modalHeading('Talebi İptal Et')
                ->modalDescription('Bu işlem geri alınamaz. Devam etmek istiyor musunuz?')
                ->visible(fn (): bool => in_array($this->record->status, ['open', 'locked']))
                ->action(function () {
                    $this->record->update(['status' => 'cancelled']);
                    Notification::make()
                        ->title('Talep iptal edildi')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('complete')
                ->label('Tamamlandı İşaretle')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->modalHeading('İşi Tamamla')
                ->modalDescription('İş tamamlandı olarak işaretlenecek.')
                ->visible(fn (): bool => $this->record->status === 'locked')
                ->action(function () {
                    $this->record->update(['status' => 'completed']);
                    Notification::make()
                        ->title('Talep tamamlandı olarak işaretlendi')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Talep Detayları')
                    ->icon('heroicon-o-document-text')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('service.name')
                            ->label('Hizmet')
                            ->icon('heroicon-o-wrench-screwdriver'),
                        Infolists\Components\TextEntry::make('subServices.name')
                            ->label('Alt Hizmetler')
                            ->badge()
                            ->separator(', '),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Açıklama')
                            ->columnSpanFull()
                            ->markdown(),
                    ]),
                Infolists\Components\Section::make('Konum & Tarih')
                    ->icon('heroicon-o-map-pin')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('location.name')
                            ->label('Konum')
                            ->icon('heroicon-o-map-pin')
                            ->formatStateUsing(fn ($record) => $record->location?->name . ($record->location?->parent ? ', ' . $record->location->parent->name : '')),
                        Infolists\Components\TextEntry::make('preferred_date')
                            ->label('Tercih Edilen Tarih')
                            ->date('d.m.Y')
                            ->icon('heroicon-o-calendar')
                            ->placeholder('Esnek'),
                        Infolists\Components\TextEntry::make('address')
                            ->label('Adres')
                            ->columnSpanFull()
                            ->placeholder('Belirtilmedi'),
                    ]),
                Infolists\Components\Section::make('Durum')
                    ->icon('heroicon-o-signal')
                    ->columns(3)
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
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
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Oluşturulma')
                            ->dateTime('d.m.Y H:i'),
                        Infolists\Components\TextEntry::make('offers_count')
                            ->label('Gelen Teklifler')
                            ->state(fn ($record) => $record->offers()->count())
                            ->badge()
                            ->color('success'),
                    ]),
            ]);
    }
}
