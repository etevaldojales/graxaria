<?php

namespace App\Filament\Resources\VehicleCheckins\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class VehicleCheckinForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Viagem & Equipe')
                    ->schema([
                        DatePicker::make('check_date')
                            ->label('Data do Check-in')
                            ->default(now())
                            ->required(),
                        Select::make('vehicle_id')
                            ->label('Veículo')
                            ->relationship('vehicle', 'plate')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $lastCheckin = \App\Models\VehicleCheckin::where('vehicle_id', $state)
                                        ->whereNotNull('odometer_end')
                                        ->orderBy('checkout_date', 'desc')
                                        ->orderBy('id', 'desc')
                                        ->first();
                                    
                                    if ($lastCheckin) {
                                        $set('odometer_start', $lastCheckin->odometer_end);
                                    }
                                }
                            }),
                        Select::make('driver_user_id')
                            ->label('Motorista')
                            ->relationship('driver', 'name', fn ($query) => $query->role('driver'))
                            ->searchable()
                            ->preload()
                            ->required(),
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
                    ])
                    ->columns(2),

                Section::make('Odômetro & Carga')
                    ->schema([
                        TextInput::make('odometer_start')
                            ->label('Odômetro Inicial (KM)')
                            ->numeric()
                            ->required(),
                        TextInput::make('odometer_end')
                            ->label('Odômetro Final (KM)')
                            ->numeric()
                            ->nullable(),
                        DateTimePicker::make('checkout_date')
                            ->label('Data/Hora de Retorno (Check-out)')
                            ->nullable(),
                        TextInput::make('num_drums')
                            ->label('Qtd. de Bombonas')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Itens de Segurança')
                    ->schema([
                        Grid::make(5)
                            ->schema([
                                Toggle::make('check_tires')
                                    ->label('Pneus OK')
                                    ->default(true),
                                Toggle::make('check_brakes')
                                    ->label('Freios OK')
                                    ->default(true),
                                Toggle::make('check_lights')
                                    ->label('Luzes OK')
                                    ->default(true),
                                Toggle::make('check_oil')
                                    ->label('Óleo OK')
                                    ->default(true),
                                Toggle::make('check_wipers')
                                    ->label('Limpadores OK')
                                    ->default(true),
                            ]),
                        Toggle::make('is_impeditivo')
                            ->label('Problema Impeditivo (Veículo não liberado para viagem)')
                            ->default(false)
                            ->live(),
                        Textarea::make('obs')
                            ->label('Observações')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
