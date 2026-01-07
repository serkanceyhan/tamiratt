<?php

namespace App\Filament\Resources\ServiceQuestionResource\Pages;

use App\Filament\Resources\ServiceQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceQuestion extends EditRecord
{
    protected static string $resource = ServiceQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
