<?php

namespace App\Filament\Resources\CreditCardRequestResource\Pages;

use App\Filament\Resources\CreditCardRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreditCardRequests extends ListRecords
{
    protected static string $resource = CreditCardRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
