<?php

namespace App\Filament\Provider\Resources\MyOffersResource\Pages;

use App\Filament\Provider\Resources\MyOffersResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMyOffer extends ViewRecord
{
    protected static string $resource = MyOffersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('call')
                ->label('Ara')
                ->icon('heroicon-m-phone')
                ->color('success')
                ->url(fn () => 'tel:' . $this->record->quote?->email) // Should be phone
                ->visible(fn () => $this->record->status === 'accepted'),
            Actions\Action::make('whatsapp')
                ->label('WhatsApp')
                ->icon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('success')
                ->url(fn () => 'https://wa.me/90' . preg_replace('/[^0-9]/', '', $this->record->quote?->email ?? ''))
                ->openUrlInNewTab()
                ->visible(fn () => $this->record->status === 'accepted'),
            Actions\Action::make('withdraw')
                ->label('Geri Çek')
                ->icon('heroicon-m-arrow-uturn-left')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Teklifi Geri Çek')
                ->modalDescription('Bu teklifi geri çekmek istediğinizden emin misiniz?')
                ->action(function () {
                    $this->record->update(['status' => 'withdrawn']);
                    $this->redirect(MyOffersResource::getUrl('index'));
                })
                ->visible(fn () => $this->record->status === 'pending'),
        ];
    }
}
