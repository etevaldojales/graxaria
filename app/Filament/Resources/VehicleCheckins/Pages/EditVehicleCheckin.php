<?php

namespace App\Filament\Resources\VehicleCheckins\Pages;

use App\Filament\Resources\VehicleCheckins\VehicleCheckinResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicleCheckin extends EditRecord
{
    protected static string $resource = VehicleCheckinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
