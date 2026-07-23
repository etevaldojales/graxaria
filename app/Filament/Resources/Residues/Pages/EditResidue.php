<?php

namespace App\Filament\Resources\Residues\Pages;

use App\Filament\Resources\Residues\ResidueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResidue extends EditRecord
{
    protected static string $resource = ResidueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
