<?php

namespace App\Filament\Resources\Collections\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class CollectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('supplier_id')
                    ->label('Fornecedor')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        $set('items', []);
                    }),
                DateTimePicker::make('collection_date')
                    ->label('Data da Coleta')
                    ->required()
                    ->default(now()),
                
                Repeater::make('items')
                    ->relationship('items')
                    ->label('Itens Coletados')
                    ->createItemButtonLabel('Adicionar Item de Coleta')
                    ->schema([
                        Select::make('residue_id')
                            ->label('Resíduo')
                            ->relationship('residue', 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $supplierId = $get('../../../supplier_id');
                                if ($supplierId && $state) {
                                    $price = \App\Models\SupplierProductPrice::where('supplier_id', $supplierId)
                                        ->where('residue_id', $state)
                                        ->value('price_per_kg') ?? 0.00;
                                    $set('price_per_kg', $price);
                                    
                                    $weight = (float)($get('weight') ?? 0.0);
                                    $set('total_cost', round($weight * $price, 2));
                                }
                            }),
                        TextInput::make('weight')
                            ->label('Peso (kg)')
                            ->required()
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $price = (float)($get('price_per_kg') ?? 0.0);
                                $set('total_cost', round((float)$state * $price, 2));
                            }),
                        TextInput::make('price_per_kg')
                            ->label('Preço por KG')
                            ->required()
                            ->numeric()
                            ->prefix('R$')
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $weight = (float)($get('weight') ?? 0.0);
                                $set('total_cost', round($weight * (float)$state, 2));
                            }),
                        TextInput::make('total_cost')
                            ->label('Custo Total')
                            ->required()
                            ->numeric()
                            ->prefix('R$')
                            ->readOnly()
                            ->default(0.00),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
                
                Select::make('driver_user_id')
                    ->label('Motorista')
                    ->relationship('driver', 'name', fn ($query) => $query->role('driver'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $user = \App\Models\User::find($state);
                            if ($user) {
                                $set('driver_name', $user->name);
                            }
                        } else {
                            $set('driver_name', null);
                        }
                    }),
                Select::make('vehicle_id')
                    ->label('Veículo')
                    ->relationship('vehicle', 'plate')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $vehicle = \App\Models\Vehicle::find($state);
                            if ($vehicle) {
                                $set('vehicle_plate', $vehicle->plate);
                            }
                        } else {
                            $set('vehicle_plate', null);
                        }
                    }),
                Select::make('helper_id')
                    ->label('Ajudante 1')
                    ->relationship('helper', 'name', fn ($query) => $query->where('is_active', true))
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Select::make('helper_2_id')
                    ->label('Ajudante 2')
                    ->relationship('helper2', 'name', fn ($query) => $query->where('is_active', true))
                    ->searchable()
                    ->preload()
                    ->nullable(),
                
                Hidden::make('driver_name'),
                Hidden::make('vehicle_plate'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Agendada' => 'Agendada',
                        'Coletada' => 'Coletada',
                        'Cancelada' => 'Cancelada',
                    ])
                    ->required()
                    ->default('Agendada'),
                Select::make('batch_id')
                    ->label('Lote de Processamento')
                    ->relationship('batch', 'batch_code')
                    ->searchable()
                    ->preload()
                    ->default(null),
            ]);
    }
}
