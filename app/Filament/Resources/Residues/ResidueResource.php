<?php

namespace App\Filament\Resources\Residues;

use App\Filament\Resources\Residues\Pages\CreateResidue;
use App\Filament\Resources\Residues\Pages\EditResidue;
use App\Filament\Resources\Residues\Pages\ListResidues;
use App\Filament\Resources\Residues\Schemas\ResidueForm;
use App\Filament\Resources\Residues\Tables\ResiduesTable;
use App\Models\Residue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ResidueResource extends Resource
{
    protected static ?string $model = Residue::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Resíduos';

    protected static ?string $modelLabel = 'Resíduo';

    protected static ?string $pluralModelLabel = 'Resíduos';

    protected static string|\UnitEnum|null $navigationGroup = 'Cadastros';

    public static function form(Schema $schema): Schema
    {
        return ResidueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResiduesTable::configure($table);
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
            'index' => ListResidues::route('/'),
            'create' => CreateResidue::route('/create'),
            'edit' => EditResidue::route('/{record}/edit'),
        ];
    }
}
