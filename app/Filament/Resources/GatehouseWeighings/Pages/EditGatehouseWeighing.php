<?php

namespace App\Filament\Resources\GatehouseWeighings\Pages;

use App\Filament\Resources\GatehouseWeighings\GatehouseWeighingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGatehouseWeighing extends EditRecord
{
    protected static string $resource = GatehouseWeighingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
