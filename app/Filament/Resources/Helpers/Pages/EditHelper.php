<?php

namespace App\Filament\Resources\Helpers\Pages;

use App\Filament\Resources\Helpers\HelperResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHelper extends EditRecord
{
    protected static string $resource = HelperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
