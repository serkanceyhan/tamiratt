<?php

namespace App\Filament\Provider\Resources\WonJobsResource\Pages;

use App\Filament\Provider\Resources\WonJobsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWonJob extends ViewRecord
{
    protected static string $resource = WonJobsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('call')
                ->label('Müşteriyi Ara')
                ->icon('heroicon-m-phone')
                ->color('success')
                ->url(fn () => 'tel:' . $this->record->quote?->email) // Should be phone
                ->size('lg'),
            Actions\Action::make('whatsapp')
                ->label('WhatsApp')
                ->icon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('success')
                ->url(fn () => 'https://wa.me/90' . preg_replace('/[^0-9]/', '', $this->record->quote?->email ?? ''))
                ->openUrlInNewTab(),
        ];
    }
}
