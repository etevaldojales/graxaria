<?php

namespace App\Filament\Resources\Residues\Pages;

use App\Filament\Resources\Residues\ResidueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResidues extends ListRecords
{
    protected static string $resource = ResidueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
