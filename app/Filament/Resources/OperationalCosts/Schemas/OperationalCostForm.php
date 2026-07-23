<?php

namespace App\Filament\Resources\OperationalCosts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OperationalCostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalhes da Despesa')
                    ->schema([
                        DatePicker::make('cost_date')
                            ->label('Data da Despesa')
                            ->default(now())
                            ->required(),
                        Select::make('cost_category_id')
                            ->label('Categoria da Despesa')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('description')
                            ->label('Descrição / Histórico')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('value')
                            ->label('Valor (R$)')
                            ->numeric()
                            ->prefix('R$')
                            ->required(),
                        TextInput::make('invoice_number')
                            ->label('Número da Nota/Cupom')
                            ->maxLength(255),
                        Select::make('inventory_item_id')
                            ->label('Peça (Almoxarifado)')
                            ->relationship('inventoryItem', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->live(),
                        TextInput::make('quantity')
                            ->label('Quantidade da Peça')
                            ->numeric()
                            ->default(1.00)
                            ->visible(fn ($get) => $get('inventory_item_id') !== null)
                            ->required(fn ($get) => $get('inventory_item_id') !== null),
                    ])
                    ->columns(2),

                Section::make('Veículo & Motorista')
                    ->schema([
                        Select::make('vehicle_id')
                            ->label('Veículo Associado')
                            ->relationship('vehicle', 'plate')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('driver_user_id')
                            ->label('Motorista Responsável')
                            ->relationship('driver', 'name', fn ($query) => $query->role('driver'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }
}
