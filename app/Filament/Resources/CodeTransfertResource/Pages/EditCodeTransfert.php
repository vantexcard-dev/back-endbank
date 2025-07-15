<?php

namespace App\Filament\Resources\CodeTransfertResource\Pages;

use App\Filament\Resources\CodeTransfertResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCodeTransfert extends EditRecord
{
    protected static string $resource = CodeTransfertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
