<?php

namespace App\Filament\Resources\FuelSupplies\Pages;

use App\Filament\Resources\FuelSupplies\FuelSupplyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFuelSupply extends EditRecord
{
    protected static string $resource = FuelSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
