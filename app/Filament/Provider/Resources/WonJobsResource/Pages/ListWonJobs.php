<?php

namespace App\Filament\Provider\Resources\WonJobsResource\Pages;

use App\Filament\Provider\Resources\WonJobsResource;
use Filament\Resources\Pages\ListRecords;

class ListWonJobs extends ListRecords
{
    protected static string $resource = WonJobsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
