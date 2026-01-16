<?php

namespace App\Filament\Customer\Resources\RequestResource\Pages;

use App\Filament\Customer\Resources\RequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequests extends ListRecords
{
    protected static string $resource = RequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('Yeni Talep')
                ->url('/hizmetler')
                ->icon('heroicon-o-plus'),
        ];
    }
}
