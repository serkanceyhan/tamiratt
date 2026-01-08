<?php

namespace App\Filament\Provider\Resources\MyOffersResource\Pages;

use App\Filament\Provider\Resources\MyOffersResource;
use Filament\Resources\Pages\ListRecords;

class ListMyOffers extends ListRecords
{
    protected static string $resource = MyOffersResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
