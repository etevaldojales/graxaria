<?php

namespace App\Filament\Resources\VehicleCheckins\Pages;

use App\Filament\Resources\VehicleCheckins\VehicleCheckinResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicleCheckins extends ListRecords
{
    protected static string $resource = VehicleCheckinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
