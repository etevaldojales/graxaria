<?php

namespace App\Filament\Resources\Helpers;

use App\Filament\Resources\Helpers\Pages\CreateHelper;
use App\Filament\Resources\Helpers\Pages\EditHelper;
use App\Filament\Resources\Helpers\Pages\ListHelpers;
use App\Filament\Resources\Helpers\Schemas\HelperForm;
use App\Filament\Resources\Helpers\Tables\HelpersTable;
use App\Models\Helper;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HelperResource extends Resource
{
    protected static ?string $model = Helper::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Ajudantes';

    protected static ?string $modelLabel = 'Ajudante';

    protected static ?string $pluralModelLabel = 'Ajudantes';

    protected static string|\UnitEnum|null $navigationGroup = 'Frota & Logística';

    public static function form(Schema $schema): Schema
    {
        return HelperForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HelpersTable::configure($table);
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
            'index' => ListHelpers::route('/'),
            'create' => CreateHelper::route('/create'),
            'edit' => EditHelper::route('/{record}/edit'),
        ];
    }
}
