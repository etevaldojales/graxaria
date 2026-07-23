<?php

namespace App\Filament\Resources\OperationalCosts\Pages;

use App\Filament\Resources\OperationalCosts\OperationalCostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOperationalCost extends EditRecord
{
    protected static string $resource = OperationalCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
