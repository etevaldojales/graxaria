<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
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
                    ->label('CNPJ/CPF')
                    ->default(null)
                    ->maxLength(255),
                TextInput::make('company_name')
                    ->label('Razão Social')
                    ->default(null)
                    ->maxLength(255),
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
            ]);
    }
}
