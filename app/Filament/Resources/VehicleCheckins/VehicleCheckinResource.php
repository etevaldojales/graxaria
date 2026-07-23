<?php

namespace App\Filament\Resources\VehicleCheckins;

use App\Filament\Resources\VehicleCheckins\Pages\CreateVehicleCheckin;
use App\Filament\Resources\VehicleCheckins\Pages\EditVehicleCheckin;
use App\Filament\Resources\VehicleCheckins\Pages\ListVehicleCheckins;
use App\Filament\Resources\VehicleCheckins\Schemas\VehicleCheckinForm;
use App\Filament\Resources\VehicleCheckins\Tables\VehicleCheckinsTable;
use App\Models\VehicleCheckin;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleCheckinResource extends Resource
{
    protected static ?string $model = VehicleCheckin::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Checklists de Viagem';

    protected static ?string $modelLabel = 'Checklist';

    protected static ?string $pluralModelLabel = 'Checklists de Viagem';

    protected static string|\UnitEnum|null $navigationGroup = 'Frota & Logística';

    public static function form(Schema $schema): Schema
    {
        return VehicleCheckinForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleCheckinsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicleCheckins::route('/'),
            'create' => CreateVehicleCheckin::route('/create'),
            'edit' => EditVehicleCheckin::route('/{record}/edit'),
        ];
    }
}
