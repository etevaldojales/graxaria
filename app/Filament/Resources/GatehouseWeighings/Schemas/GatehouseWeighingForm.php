<?php

namespace App\Filament\Resources\GatehouseWeighings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GatehouseWeighingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificação da Pesagem')
                    ->schema([
                        DateTimePicker::make('weighing_date')
                            ->label('Data/Hora')
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
                        TextInput::make('trip_number')
                            ->label('Nº da Viagem (no dia)')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Pesos (KG)')
                    ->schema([
                        TextInput::make('gross_weight')
                            ->label('Peso Bruto')
                            ->numeric()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $tare = (float)($get('tare_weight') ?? 0.0);
                                if ($tare > 0) {
                                    $set('net_weight', round((float)$state - $tare, 2));
                                } else {
                                    $set('net_weight', null);
                                }
                            }),
                        TextInput::make('tare_weight')
                            ->label('Tara')
                            ->numeric()
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                $gross = (float)($get('gross_weight') ?? 0.0);
                                if ($gross > 0 && $state > 0) {
                                    $set('net_weight', round($gross - (float)$state, 2));
                                    $set('status', 'Concluído');
                                } else {
                                    $set('net_weight', null);
                                    $set('status', 'Pendente_Tara');
                                }
                            }),
                        TextInput::make('net_weight')
                            ->label('Peso Líquido')
                            ->numeric()
                            ->readOnly()
                            ->nullable(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Pendente_Tara' => 'Pendente Tara',
                                'Concluído' => 'Concluído',
                                'Cancelado' => 'Cancelado',
                            ])
                            ->default('Pendente_Tara')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
