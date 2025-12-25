<?php

namespace App\Filament\Resources\LocalizedStringResource\Pages;

use App\Filament\Resources\LocalizedStringResource;
use Filament\Resources\Pages\ListRecords;

class ListLocalizedStrings extends ListRecords
{
    protected static string $resource = LocalizedStringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
