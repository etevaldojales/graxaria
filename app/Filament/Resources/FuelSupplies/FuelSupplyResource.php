<?php

namespace App\Filament\Resources\FuelSupplies;

use App\Filament\Resources\FuelSupplies\Pages\CreateFuelSupply;
use App\Filament\Resources\FuelSupplies\Pages\EditFuelSupply;
use App\Filament\Resources\FuelSupplies\Pages\ListFuelSupplies;
use App\Filament\Resources\FuelSupplies\Schemas\FuelSupplyForm;
use App\Filament\Resources\FuelSupplies\Tables\FuelSuppliesTable;
use App\Models\FuelSupply;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FuelSupplyResource extends Resource
{
    protected static ?string $model = FuelSupply::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static ?string $navigationLabel = 'Abastecimentos';

    protected static ?string $modelLabel = 'Abastecimento';

    protected static ?string $pluralModelLabel = 'Abastecimentos';

    protected static string|\UnitEnum|null $navigationGroup = 'Financeiro';

    public static function form(Schema $schema): Schema
    {
        return FuelSupplyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FuelSuppliesTable::configure($table);
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
            'index' => ListFuelSupplies::route('/'),
            'create' => CreateFuelSupply::route('/create'),
            'edit' => EditFuelSupply::route('/{record}/edit'),
        ];
    }
}
