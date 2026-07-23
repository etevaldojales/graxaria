<?php

namespace App\Filament\Resources\InventoryItems\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class InventoryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome da Peça/Item')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('sku')
                    ->label('SKU / Código')
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->extraInputAttributes(['style' => 'text-transform: uppercase']),
                TextInput::make('stock')
                    ->label('Estoque Inicial')
                    ->numeric()
                    ->default(0.00)
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->dehydrated(),
                TextInput::make('min_stock')
                    ->label('Estoque Mínimo')
                    ->numeric()
                    ->default(0.00)
                    ->required(),
                Select::make('unit')
                    ->label('Unidade')
                    ->options([
                        'UN' => 'Unidade (UN)',
                        'L' => 'Litro (L)',
                        'KG' => 'Quilograma (KG)',
                        'M' => 'Metro (M)',
                        'JG' => 'Jogo (JG)',
                    ])
                    ->default('UN')
                    ->required(),
            ]);
    }
}
