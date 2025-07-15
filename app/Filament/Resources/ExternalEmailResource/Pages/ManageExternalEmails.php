<?php

namespace App\Filament\Resources\ExternalEmailResource\Pages;

use App\Filament\Resources\ExternalEmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageExternalEmails extends ManageRecords
{
    protected static string $resource = ExternalEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
