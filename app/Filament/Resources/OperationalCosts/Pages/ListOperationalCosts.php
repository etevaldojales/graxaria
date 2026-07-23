<?php

namespace App\Filament\Resources\OperationalCosts\Pages;

use App\Filament\Resources\OperationalCosts\OperationalCostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOperationalCosts extends ListRecords
{
    protected static string $resource = OperationalCostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
