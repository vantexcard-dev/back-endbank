<?php

namespace App\Filament\Resources\BankDocumentResource\Pages;

use App\Filament\Resources\BankDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBankDocument extends EditRecord
{
    protected static string $resource = BankDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
