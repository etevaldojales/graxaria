<?php

namespace App\Filament\Resources\Batches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('batch_code')
                    ->label('Código do Lote')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Fim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('input_weight')
                    ->label('Entrada (kg)')
                    ->numeric(2, ',', '.')
                    ->sortable(),
                TextColumn::make('output_tallow_weight')
                    ->label('Sebo (kg)')
                    ->numeric(2, ',', '.')
                    ->sortable(),
                TextColumn::make('output_meal_weight')
                    ->label('Farinha (kg)')
                    ->numeric(2, ',', '.')
                    ->sortable(),
                TextColumn::make('yield_percentage')
                    ->label('Rendimento')
                    ->numeric(2, ',', '.')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Concluído' => 'success',
                        default => 'warning',
                    })
                    ->searchable(),
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
