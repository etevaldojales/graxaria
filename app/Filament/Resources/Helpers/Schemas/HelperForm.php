<?php

namespace App\Filament\Resources\Helpers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HelperForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome do Ajudante')
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telefone')
                    ->tel()
                    ->maxLength(20),
                Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true)
                    ->required(),
            ]);
    }
}
