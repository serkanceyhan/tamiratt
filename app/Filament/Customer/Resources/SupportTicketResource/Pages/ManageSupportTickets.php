<?php

namespace App\Filament\Customer\Resources\SupportTicketResource\Pages;

use App\Filament\Customer\Resources\SupportTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSupportTickets extends ManageRecords
{
    protected static string $resource = SupportTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
