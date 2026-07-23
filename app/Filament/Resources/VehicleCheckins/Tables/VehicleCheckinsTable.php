<?php

namespace App\Filament\Resources\VehicleCheckins\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class VehicleCheckinsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('check_date')
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
                TextColumn::make('odometer_start')
                    ->label('Odômetro Inicial (KM)')
                    ->sortable(),
                TextColumn::make('odometer_end')
                    ->label('Odômetro Final (KM)')
                    ->placeholder('Pendente')
                    ->sortable(),
                IconColumn::make('is_impeditivo')
                    ->label('Impeditivo')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(fn ($record): string => $record->odometer_end ? 'Concluído' : 'Em Viagem')
                    ->color(fn (string $state): string => match ($state) {
                        'Concluído' => 'success',
                        'Em Viagem' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('checkout_date')
                    ->label('Retorno')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-')
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
