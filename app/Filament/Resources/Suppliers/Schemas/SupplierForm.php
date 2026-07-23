<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                TextInput::make('document')
                    ->label('Documento (CPF/CNPJ)')
                    ->default(null)
                    ->maxLength(255),
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'Açougue' => 'Açougue',
                        'Supermercado' => 'Supermercado',
                        'Frigorífico' => 'Frigorífico',
                        'Outros' => 'Outros',
                    ])
                    ->required()
                    ->default('Açougue'),
                TextInput::make('phone')
                    ->label('Telefone')
                    ->tel()
                    ->default(null)
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->default(null)
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('Endereço')
                    ->default(null)
                    ->maxLength(255),
                TextInput::make('price_per_kg')
                    ->label('Preço por KG (Base)')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->default(0.0),
                Select::make('route_id')
                    ->label('Rota / Setor')
                    ->relationship('route', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
