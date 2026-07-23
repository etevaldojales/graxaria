<?php

namespace App\Filament\Resources\GatehouseWeighings;

use App\Filament\Resources\GatehouseWeighings\Pages\CreateGatehouseWeighing;
use App\Filament\Resources\GatehouseWeighings\Pages\EditGatehouseWeighing;
use App\Filament\Resources\GatehouseWeighings\Pages\ListGatehouseWeighings;
use App\Filament\Resources\GatehouseWeighings\Schemas\GatehouseWeighingForm;
use App\Filament\Resources\GatehouseWeighings\Tables\GatehouseWeighingsTable;
use App\Models\GatehouseWeighing;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GatehouseWeighingResource extends Resource
{
    protected static ?string $model = GatehouseWeighing::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $navigationLabel = 'Balancão (Portaria)';

    protected static ?string $modelLabel = 'Pesagem';

    protected static ?string $pluralModelLabel = 'Pesagens (Portaria)';

    protected static string|\UnitEnum|null $navigationGroup = 'Operações';

    public static function form(Schema $schema): Schema
    {
        return GatehouseWeighingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GatehouseWeighingsTable::configure($table);
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
            'index' => ListGatehouseWeighings::route('/'),
            'create' => CreateGatehouseWeighing::route('/create'),
            'edit' => EditGatehouseWeighing::route('/{record}/edit'),
        ];
    }
}
