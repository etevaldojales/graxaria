<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('product_type')
                    ->label('Tipo de Produto')
                    ->options([
                        'Sebo' => 'Sebo',
                        'Farinha' => 'Farinha',
                        'Outros' => 'Outros',
                    ])
                    ->required(),
                TextInput::make('weight')
                    ->label('Peso (kg)')
                    ->required()
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(fn ($state, $get, $set) => $set('total_value', round((float)$state * (float)($get('price_per_kg') ?? 0.0), 2))),
                TextInput::make('price_per_kg')
                    ->label('Preço por KG')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->live()
                    ->afterStateUpdated(fn ($state, $get, $set) => $set('total_value', round((float)$state * (float)($get('weight') ?? 0.0), 2))),
                TextInput::make('total_value')
                    ->label('Valor Total')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->readOnly()
                    ->default(0.0),
                DateTimePicker::make('sale_date')
                    ->label('Data da Venda')
                    ->required()
                    ->default(now()),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Pendente' => 'Pendente',
                        'Pago' => 'Pago',
                        'Cancelado' => 'Cancelado',
                    ])
                    ->required()
                    ->default('Pendente'),
            ]);
    }
}
