<?php

namespace App\Filament\Resources\GatehouseWeighings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GatehouseWeighingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('weighing_date')
                    ->label('Data/Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('vehicle.plate')
                    ->label('Veículo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('driver.name')
                    ->label('Motorista')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('trip_number')
                    ->label('Viagem')
                    ->sortable(),
                TextColumn::make('gross_weight')
                    ->label('Peso Bruto')
                    ->numeric(2, ',', '.')
                    ->sortable(),
                TextColumn::make('tare_weight')
                    ->label('Tara')
                    ->placeholder('Pendente')
                    ->numeric(2, ',', '.')
                    ->sortable(),
                TextColumn::make('net_weight')
                    ->label('Peso Líquido')
                    ->numeric(2, ',', '.')
                    ->sortable()
                    ->description(function ($record) {
                        if (!$record->net_weight || !$record->weighing_date) {
                            return null;
                        }
                        
                        $date = $record->weighing_date->toDateString();
                        // Sum weights of all collection items for this vehicle on this day
                        $sumCollections = \App\Models\CollectionItem::whereHas('collection', function ($q) use ($record, $date) {
                            $q->where('vehicle_id', $record->vehicle_id)
                              ->whereDate('collection_date', $date);
                        })->sum('weight');
                        
                        if ($sumCollections == 0) {
                            return 'Sem coletas no dia';
                        }
                        
                        $diff = $record->net_weight - $sumCollections;
                        $diffFormatted = number_format(abs($diff), 2, ',', '.');
                        
                        if (abs($diff) < 0.01) {
                            return 'Diferença: 0 kg (Batido)';
                        } elseif ($diff > 0) {
                            return "Sobra fábrica: +{$diffFormatted} kg";
                        } else {
                            return "Perda carga: -{$diffFormatted} kg";
                        }
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Concluído' => 'success',
                        'Pendente_Tara' => 'warning',
                        'Cancelado' => 'danger',
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
