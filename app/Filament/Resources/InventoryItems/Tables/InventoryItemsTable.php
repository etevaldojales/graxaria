<?php

namespace App\Filament\Resources\InventoryItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoryItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU/Código')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('name')
                    ->label('Item')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Estoque Atual')
                    ->numeric(2, ',', '.')
                    ->sortable()
                    ->color(fn ($record): string => $record->stock <= $record->min_stock ? 'danger' : 'success')
                    ->description(fn ($record): ?string => $record->stock <= $record->min_stock ? 'Abaixo do mínimo!' : null),
                TextColumn::make('min_stock')
                    ->label('Estoque Mínimo')
                    ->numeric(2, ',', '.')
                    ->sortable(),
                TextColumn::make('unit')
                    ->label('Unidade')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Última Atualização')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
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
