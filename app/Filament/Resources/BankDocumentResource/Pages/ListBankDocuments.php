<?php

namespace App\Filament\Resources\BankDocumentResource\Pages;

use App\Filament\Resources\BankDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBankDocuments extends ListRecords
{
    protected static string $resource = BankDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
