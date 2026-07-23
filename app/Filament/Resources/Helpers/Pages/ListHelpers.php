<?php

namespace App\Filament\Resources\Helpers\Pages;

use App\Filament\Resources\Helpers\HelperResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHelpers extends ListRecords
{
    protected static string $resource = HelperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
