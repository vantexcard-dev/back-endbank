<?php

namespace App\Filament\Resources\CreditCardRequestResource\Pages;

use App\Filament\Resources\CreditCardRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreditCardRequest extends EditRecord
{
    protected static string $resource = CreditCardRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
