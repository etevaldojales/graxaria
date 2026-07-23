<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações Gerais')
                    ->schema([
                        TextInput::make('plate')
                            ->label('Placa')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->extraInputAttributes(['style' => 'text-transform: uppercase']),
                        TextInput::make('brand_model')
                            ->label('Marca / Modelo')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('color')
                            ->label('Cor')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('year_fabrication')
                            ->label('Ano de Fabricação')
                            ->numeric()
                            ->required(),
                        TextInput::make('year_model')
                            ->label('Ano do Modelo')
                            ->numeric()
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Ativo' => 'Ativo',
                                'Manutenção' => 'Manutenção',
                                'Inativo' => 'Inativo',
                            ])
                            ->default('Ativo')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Documentação')
                    ->schema([
                        TextInput::make('dut')
                            ->label('DUT')
                            ->maxLength(255),
                        TextInput::make('renavan')
                            ->label('RENAVAN')
                            ->maxLength(255),
                        Select::make('driver_user_id')
                            ->label('Motorista Responsável')
                            ->relationship('driver', 'name', fn ($query) => $query->role('driver'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->columns(2),

                Section::make('Terceirização')
                    ->schema([
                        Toggle::make('is_outsourced')
                            ->label('Veículo Terceirizado')
                            ->live()
                            ->default(false),
                        TextInput::make('owner_name')
                            ->label('Nome do Proprietário')
                            ->maxLength(255)
                            ->visible(fn ($get) => $get('is_outsourced')),
                        TextInput::make('owner_phone')
                            ->label('Telefone do Proprietário')
                            ->maxLength(50)
                            ->visible(fn ($get) => $get('is_outsourced')),
                    ])
                    ->columns(2),
            ]);
    }
}
