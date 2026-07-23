<?php

namespace App\Filament\Resources\OperationalCosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OperationalCostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cost_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('vehicle.plate')
                    ->label('Veículo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('driver.name')
                    ->label('Motorista')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Nenhum'),
                TextColumn::make('category.name')
                    ->label('Categoria')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable(),
                TextColumn::make('value')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('invoice_number')
                    ->label('Nota/Cupom')
                    ->searchable()
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
