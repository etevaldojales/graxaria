<?php

namespace App\Filament\Resources\GatehouseWeighings\Pages;

use App\Filament\Resources\GatehouseWeighings\GatehouseWeighingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGatehouseWeighings extends ListRecords
{
    protected static string $resource = GatehouseWeighingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
