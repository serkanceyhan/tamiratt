<?php

namespace App\Filament\Provider\Resources\LeadResource\Pages;

use App\Filament\Provider\Resources\LeadResource;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
