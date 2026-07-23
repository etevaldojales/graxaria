<?php

namespace App\Filament\Resources\Suppliers\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ProductPricesRelationManager extends RelationManager
{
    protected static string $relationship = 'productPrices';

    protected static ?string $title = 'Preços Dinâmicos de Resíduos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('residue_id')
                    ->label('Resíduo')
                    ->relationship('residue', 'name')
                    ->required()
                    ->unique(modifyRuleUsing: function ($rule) {
                        return $rule->where('supplier_id', $this->getOwnerRecord()->id);
                    }, ignoreRecord: true),
                TextInput::make('price_per_kg')
                    ->label('Preço por KG')
                    ->numeric()
                    ->prefix('R$')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('residue.name')
            ->columns([
                TextColumn::make('residue.name')
                    ->label('Resíduo')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('price_per_kg')
                    ->label('Preço por KG')
                    ->money('BRL')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Definir Preço'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
