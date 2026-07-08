<?php

namespace App\Filament\Resources\Collections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CollectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier.name')
                    ->label('Fornecedor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('collection_date')
                    ->label('Data da Coleta')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('residue_type')
                    ->label('Tipo de Resíduo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ossos' => 'danger',
                        'Gordura' => 'warning',
                        'Miúdos' => 'info',
                        default => 'success',
                    })
                    ->searchable(),
                TextColumn::make('weight')
                    ->label('Peso (kg)')
                    ->numeric(2, ',', '.')
                    ->sortable(),
                TextColumn::make('price_per_kg')
                    ->label('Preço/KG')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->label('Custo Total')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('driver_name')
                    ->label('Motorista')
                    ->searchable(),
                TextColumn::make('vehicle_plate')
                    ->label('Placa')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Coletada' => 'success',
                        'Cancelada' => 'danger',
                        default => 'warning',
                    })
                    ->searchable(),
                TextColumn::make('batch.batch_code')
                    ->label('Lote')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
