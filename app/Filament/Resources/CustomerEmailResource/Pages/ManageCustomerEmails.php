<?php

namespace App\Filament\Resources\CustomerEmailResource\Pages;

use App\Filament\Resources\CustomerEmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCustomerEmails extends ManageRecords
{
    protected static string $resource = CustomerEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
