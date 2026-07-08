<?php

namespace App\Filament\Resources\Collections\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
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
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Automatically fill the price per kg from the supplier default price if not set
                        if ($state) {
                            $supplier = \App\Models\Supplier::find($state);
                            if ($supplier) {
                                $set('price_per_kg', $supplier->price_per_kg);
                                $weight = (float)($get('weight') ?? 0.0);
                                $set('total_cost', round($weight * $supplier->price_per_kg, 2));
                            }
                        }
                    }),
                DateTimePicker::make('collection_date')
                    ->label('Data da Coleta')
                    ->required()
                    ->default(now()),
                Select::make('residue_type')
                    ->label('Tipo de Resíduo')
                    ->options([
                        'Ossos' => 'Ossos',
                        'Gordura' => 'Gordura',
                        'Miúdos' => 'Miúdos',
                        'Misto' => 'Misto',
                    ])
                    ->required()
                    ->default('Misto'),
                TextInput::make('weight')
                    ->label('Peso (kg)')
                    ->required()
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(fn ($state, $get, $set) => $set('total_cost', round((float)$state * (float)($get('price_per_kg') ?? 0.0), 2))),
                TextInput::make('price_per_kg')
                    ->label('Preço por KG')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->live()
                    ->afterStateUpdated(fn ($state, $get, $set) => $set('total_cost', round((float)$state * (float)($get('weight') ?? 0.0), 2))),
                TextInput::make('total_cost')
                    ->label('Custo Total')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->readOnly()
                    ->default(0.0),
                TextInput::make('driver_name')
                    ->label('Nome do Motorista')
                    ->default(null)
                    ->maxLength(255),
                TextInput::make('vehicle_plate')
                    ->label('Placa do Veículo')
                    ->default(null)
                    ->maxLength(255),
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
