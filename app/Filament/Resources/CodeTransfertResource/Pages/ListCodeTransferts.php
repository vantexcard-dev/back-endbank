<?php

namespace App\Filament\Resources\CodeTransfertResource\Pages;

use App\Filament\Resources\CodeTransfertResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCodeTransferts extends ListRecords
{
    protected static string $resource = CodeTransfertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
