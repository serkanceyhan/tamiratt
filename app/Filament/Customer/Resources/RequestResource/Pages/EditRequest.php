<?php

namespace App\Filament\Customer\Resources\RequestResource\Pages;

use App\Filament\Customer\Resources\RequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditRequest extends EditRecord
{
    protected static string $resource = RequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Talep güncellendi')
            ->body('Değişiklikler kaydedildi.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Only allow editing certain fields
        return [
            'description' => $data['description'] ?? $this->record->description,
            'preferred_date' => $data['preferred_date'] ?? $this->record->preferred_date,
        ];
    }
}
