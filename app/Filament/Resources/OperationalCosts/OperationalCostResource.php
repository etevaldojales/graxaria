<?php

namespace App\Filament\Resources\OperationalCosts;

use App\Filament\Resources\OperationalCosts\Pages\CreateOperationalCost;
use App\Filament\Resources\OperationalCosts\Pages\EditOperationalCost;
use App\Filament\Resources\OperationalCosts\Pages\ListOperationalCosts;
use App\Filament\Resources\OperationalCosts\Schemas\OperationalCostForm;
use App\Filament\Resources\OperationalCosts\Tables\OperationalCostsTable;
use App\Models\OperationalCost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OperationalCostResource extends Resource
{
    protected static ?string $model = OperationalCost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Despesas Operacionais';

    protected static ?string $modelLabel = 'Despesa';

    protected static ?string $pluralModelLabel = 'Despesas Operacionais';

    protected static string|\UnitEnum|null $navigationGroup = 'Financeiro';

    public static function form(Schema $schema): Schema
    {
        return OperationalCostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OperationalCostsTable::configure($table);
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
            'index' => ListOperationalCosts::route('/'),
            'create' => CreateOperationalCost::route('/create'),
            'edit' => EditOperationalCost::route('/{record}/edit'),
        ];
    }
}
