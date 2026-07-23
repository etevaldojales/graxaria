<?php

namespace App\Filament\Resources\FuelSupplies\Pages;

use App\Filament\Resources\FuelSupplies\FuelSupplyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFuelSupplies extends ListRecords
{
    protected static string $resource = FuelSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
