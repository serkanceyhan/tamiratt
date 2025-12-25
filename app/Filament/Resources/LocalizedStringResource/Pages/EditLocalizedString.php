<?php

namespace App\Filament\Resources\LocalizedStringResource\Pages;

use App\Filament\Resources\LocalizedStringResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\LocalizedString;

class EditLocalizedString extends EditRecord
{
    protected static string $resource = LocalizedStringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void
    {
        // Clear cache after save
        LocalizedString::clearCache();
    }
}
