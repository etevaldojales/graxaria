<?php

namespace App\Filament\Resources\Routes\RelationManagers;

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

class CommissionParametersRelationManager extends RelationManager
{
    protected static string $relationship = 'commissionParameters';

    protected static ?string $title = 'Parâmetros de Comissão por Resíduo';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('residue_id')
                    ->label('Resíduo')
                    ->relationship('residue', 'name')
                    ->required()
                    ->unique(modifyRuleUsing: function ($rule) {
                        return $rule->where('route_id', $this->getOwnerRecord()->id);
                    }, ignoreRecord: true),
                TextInput::make('commission_per_kg_driver')
                    ->label('Comissão/KG Motorista')
                    ->numeric()
                    ->required()
                    ->default(0.0000)
                    ->step(0.0001),
                TextInput::make('commission_per_kg_helper')
                    ->label('Comissão/KG Ajudante')
                    ->numeric()
                    ->required()
                    ->default(0.0000)
                    ->step(0.0001),
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
                TextColumn::make('commission_per_kg_driver')
                    ->label('Comissão/KG Motorista')
                    ->numeric(4, ',', '.')
                    ->sortable(),
                TextColumn::make('commission_per_kg_helper')
                    ->label('Comissão/KG Ajudante')
                    ->numeric(4, ',', '.')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Adicionar Parâmetro'),
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
