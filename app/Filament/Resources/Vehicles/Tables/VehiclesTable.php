<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plate')
                    ->label('Placa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('brand_model')
                    ->label('Marca/Modelo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('color')
                    ->label('Cor'),
                TextColumn::make('year_model')
                    ->label('Ano Model')
                    ->sortable(),
                IconColumn::make('is_outsourced')
                    ->label('Terceirizado')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('driver.name')
                    ->label('Motorista')
                    ->placeholder('Nenhum')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ativo' => 'success',
                        'Manutenção' => 'warning',
                        'Inativo' => 'danger',
                        default => 'gray',
                    })
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
