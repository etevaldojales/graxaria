<?php

namespace App\Filament\Resources\FuelSupplies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FuelSuppliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supply_date')
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
                    ->sortable(),
                TextColumn::make('fuel_type')
                    ->label('Combustível')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('liters')
                    ->label('Litros')
                    ->numeric(2, ',', '.')
                    ->sortable(),
                TextColumn::make('total_value')
                    ->label('Valor Total')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('odometer')
                    ->label('Odômetro (KM)')
                    ->sortable()
                    ->description(function ($record) {
                        // Find the previous fuel supply for the same vehicle
                        $prevSupply = \App\Models\FuelSupply::where('vehicle_id', $record->vehicle_id)
                            ->where('odometer', '<', $record->odometer)
                            ->orderBy('odometer', 'desc')
                            ->first();

                        if (!$prevSupply) {
                            return 'Primeiro abastecimento';
                        }

                        $distance = $record->odometer - $prevSupply->odometer;
                        if ($record->liters > 0) {
                            $kml = round($distance / (float)$record->liters, 2);
                            return "Percorrido: {$distance} km | Média: {$kml} km/L";
                        }

                        return "Percorrido: {$distance} km";
                    }),
                TextColumn::make('coupon_number')
                    ->label('Cupom/Nota')
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
