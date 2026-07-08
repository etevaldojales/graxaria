<?php

namespace App\Filament\Resources\Batches\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class BatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('batch_code')
                    ->label('Código do Lote')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('EX: LOT-YYYYMMDD-01'),
                DateTimePicker::make('start_date')
                    ->label('Data de Início')
                    ->required()
                    ->default(now()),
                DateTimePicker::make('end_date')
                    ->label('Data de Fim')
                    ->default(null),
                TextInput::make('input_weight')
                    ->label('Peso de Entrada (kg)')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live()
                    ->afterStateUpdated(fn ($state, $get, $set) => self::calculateYield($get, $set)),
                TextInput::make('output_tallow_weight')
                    ->label('Saída - Sebo (kg)')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live()
                    ->afterStateUpdated(fn ($state, $get, $set) => self::calculateYield($get, $set)),
                TextInput::make('output_meal_weight')
                    ->label('Saída - Farinha (kg)')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live()
                    ->afterStateUpdated(fn ($state, $get, $set) => self::calculateYield($get, $set)),
                TextInput::make('yield_percentage')
                    ->label('Rendimento (%)')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('%')
                    ->readOnly(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Em Processamento' => 'Em Processamento',
                        'Concluído' => 'Concluído',
                    ])
                    ->required()
                    ->default('Em Processamento'),
            ]);
    }

    public static function calculateYield($get, $set)
    {
        $input = (float) $get('input_weight');
        $tallow = (float) $get('output_tallow_weight');
        $meal = (float) $get('output_meal_weight');
        if ($input > 0) {
            $set('yield_percentage', round((($tallow + $meal) / $input) * 100, 2));
        } else {
            $set('yield_percentage', 0.0);
        }
    }
}
