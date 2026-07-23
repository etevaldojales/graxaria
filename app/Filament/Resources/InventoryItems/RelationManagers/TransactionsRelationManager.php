<?php

namespace App\Filament\Resources\InventoryItems\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $title = 'Movimentações de Estoque';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Tipo de Movimentação')
                    ->options([
                        'Entrada' => 'Entrada (Compra/Retorno)',
                        'Saída' => 'Saída (Consumo/Descarte)',
                    ])
                    ->required(),
                TextInput::make('quantity')
                    ->label('Quantidade')
                    ->numeric()
                    ->required(),
                TextInput::make('description')
                    ->label('Descrição / Justificativa')
                    ->required()
                    ->maxLength(255),
                DateTimePicker::make('transaction_date')
                    ->label('Data/Hora')
                    ->default(now())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Data/Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Entrada' => 'success',
                        'Saída' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Quantidade')
                    ->numeric(2, ',', '.')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Lançar Movimentação'),
            ])
            ->actions([
                // Read-only history of transactions
            ])
            ->bulkActions([
                // None
            ]);
    }
}
