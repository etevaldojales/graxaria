<?php

namespace App\Filament\Resources\FuelSupplies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FuelSupplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificação do Abastecimento')
                    ->schema([
                        DatePicker::make('supply_date')
                            ->label('Data do Abastecimento')
                            ->default(now())
                            ->required(),
                        Select::make('vehicle_id')
                            ->label('Veículo')
                            ->relationship('vehicle', 'plate')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('driver_user_id')
                            ->label('Motorista')
                            ->relationship('driver', 'name', fn ($query) => $query->role('driver'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('odometer')
                            ->label('Odômetro Atual (KM)')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Valores & Litragem')
                    ->schema([
                        TextInput::make('liters')
                            ->label('Litros')
                            ->numeric()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $price = (float)($get('price_per_liter') ?? 0.0);
                                if ($price > 0) {
                                    $set('total_value', round((float)$state * $price, 2));
                                }
                            }),
                        TextInput::make('price_per_liter')
                            ->label('Preço por Litro')
                            ->numeric()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $liters = (float)($get('liters') ?? 0.0);
                                if ($liters > 0) {
                                    $set('total_value', round($liters * (float)$state, 2));
                                }
                            }),
                        TextInput::make('total_value')
                            ->label('Valor Total')
                            ->numeric()
                            ->readOnly()
                            ->prefix('R$')
                            ->required(),
                        Select::make('fuel_type')
                            ->label('Tipo de Combustível')
                            ->options([
                                'Diesel S10' => 'Diesel S10',
                                'Diesel Comum' => 'Diesel Comum',
                                'Outros' => 'Outros',
                            ])
                            ->default('Diesel S10')
                            ->required(),
                        TextInput::make('coupon_number')
                            ->label('Número do Cupom / Nota')
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }
}
