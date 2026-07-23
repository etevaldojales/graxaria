<?php

namespace App\Filament\Resources\Sales\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product_type')
                    ->label('Subproduto')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sebo' => 'warning',
                        'Farinha' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('weight')
                    ->label('Peso (kg)')
                    ->numeric(2, ',', '.')
                    ->sortable(),
                TextColumn::make('price_per_kg')
                    ->label('Preço/KG')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('total_value')
                    ->label('Valor Total')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('sale_date')
                    ->label('Data da Venda')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pago' => 'success',
                        'Cancelado' => 'danger',
                        default => 'warning',
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('generate_certificate')
                    ->label('Laudo PAC/SIF')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->visible(fn ($record) => in_array($record->product_type, ['Sebo', 'Farinha']))
                    ->mountUsing(function ($form, $record) {
                        if ($record->product_type === 'Sebo') {
                            $cert = $record->tallowCertificates()->first();
                            if ($cert) {
                                $form->fill($cert->toArray());
                            } else {
                                $form->fill([
                                    'analysis_date' => now()->toDateString(),
                                    'shipping_date' => now()->toDateString(),
                                    'production_date' => now()->format('m/Y'),
                                    'expiry_info' => '120 dias',
                                    'result_aspect' => 'Límpido e Isento de Impurezas',
                                    'result_acidity' => 'Máximo 2,0%',
                                    'result_impurities' => 'Máximo 1,0%',
                                    'result_odor' => 'Característico',
                                    'result_moisture' => 'Máximo 0,5%',
                                    'vehicle_plate' => '',
                                    'carrier_name' => '',
                                    'invoice_number' => '',
                                    'seal_number' => '',
                                    'inspected_clean_external' => true,
                                    'inspected_clean_internal' => true,
                                    'inspected_dry_internal' => true,
                                    'is_released' => true,
                                    'qa_responsible' => 'Laboratório de Qualidade',
                                    'technical_responsible' => 'Garantia de Qualidade SIF/PAC',
                                ]);
                            }
                        } elseif ($record->product_type === 'Farinha') {
                            $cert = $record->mealCertificates()->first();
                            if ($cert) {
                                $form->fill($cert->toArray());
                            } else {
                                $form->fill([
                                    'analysis_date' => now()->toDateString(),
                                    'revisao_number' => 1,
                                    'weight' => $record->weight,
                                    'invoice_number' => '',
                                    'vehicle_plate' => '',
                                    'driver_name' => '',
                                    'driver_cpf' => '',
                                    'seal_number' => '',
                                ]);
                            }
                        }
                    })
                    ->form(function ($record) {
                        if ($record->product_type === 'Sebo') {
                            return [
                                Grid::make(2)
                                    ->schema([
                                        DatePicker::make('analysis_date')
                                            ->label('Data da Análise')
                                            ->required(),
                                        DatePicker::make('shipping_date')
                                            ->label('Data de Expedição')
                                            ->required(),
                                        TextInput::make('production_date')
                                            ->label('Data de Produção')
                                            ->required(),
                                        TextInput::make('expiry_info')
                                            ->label('Prazo de Validade')
                                            ->required(),
                                    ]),
                                Section::make('Resultados de Análise Física/Química')
                                    ->schema([
                                        TextInput::make('result_aspect')
                                            ->label('Aspecto')
                                            ->required(),
                                        TextInput::make('result_acidity')
                                            ->label('Acidez (A.G.L.)')
                                            ->required(),
                                        TextInput::make('result_impurities')
                                            ->label('Impurezas')
                                            ->required(),
                                        TextInput::make('result_odor')
                                            ->label('Odor')
                                            ->required(),
                                        TextInput::make('result_moisture')
                                            ->label('Umidade e Matéria Volátil')
                                            ->required(),
                                    ])->columns(2),
                                Section::make('Dados do Transporte & Lacre')
                                    ->schema([
                                        TextInput::make('vehicle_plate')
                                            ->label('Placa do Veículo')
                                            ->required(),
                                        TextInput::make('carrier_name')
                                            ->label('Transportadora')
                                            ->required(),
                                        TextInput::make('invoice_number')
                                            ->label('Nota Fiscal (NF-e)')
                                            ->required(),
                                        TextInput::make('seal_number')
                                            ->label('Número do Lacre')
                                            ->required(),
                                    ])->columns(2),
                                Section::make('Vistoria de Conformidade Higiênica do Veículo')
                                    ->schema([
                                        Toggle::make('inspected_clean_external')
                                            ->label('Caminhão limpo externamente')
                                            ->default(true),
                                        Toggle::make('inspected_clean_internal')
                                            ->label('Caminhão limpo internamente (tanque/lona)')
                                            ->default(true),
                                        Toggle::make('inspected_dry_internal')
                                            ->label('Tanque/Lona seco internamente')
                                            ->default(true),
                                        Toggle::make('is_released')
                                            ->label('Carga liberada para expedição')
                                            ->default(true),
                                    ])->columns(2),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('qa_responsible')
                                            ->label('Resp. pela Análise (Laboratório)')
                                            ->required(),
                                        TextInput::make('technical_responsible')
                                            ->label('Resp. Técnico (Expedição)')
                                            ->required(),
                                    ]),
                            ];
                        } elseif ($record->product_type === 'Farinha') {
                            return [
                                Grid::make(2)
                                    ->schema([
                                        DatePicker::make('analysis_date')
                                            ->label('Data da Análise')
                                            ->required(),
                                        TextInput::make('revisao_number')
                                            ->label('Revisão Nº')
                                            ->numeric()
                                            ->required(),
                                        TextInput::make('invoice_number')
                                            ->label('Nota Fiscal (NF-e)')
                                            ->required(),
                                        TextInput::make('weight')
                                            ->label('Peso Expedido (KG)')
                                            ->numeric()
                                            ->required(),
                                    ]),
                                Section::make('Dados do Transporte & Lacre')
                                    ->schema([
                                        TextInput::make('vehicle_plate')
                                            ->label('Placa do Veículo')
                                            ->required(),
                                        TextInput::make('driver_name')
                                            ->label('Nome do Motorista')
                                            ->required(),
                                        TextInput::make('driver_cpf')
                                            ->label('CPF do Motorista')
                                            ->required(),
                                        TextInput::make('seal_number')
                                            ->label('Número do Lacre')
                                            ->required(),
                                    ])->columns(2),
                                Section::make('Registro de Não-Conformidades e Ações Corretivas')
                                    ->schema([
                                        Textarea::make('non_conformities')
                                            ->label('Descrição das Não-Conformidades')
                                            ->rows(2),
                                        Textarea::make('corrective_actions')
                                            ->label('Ações Corretivas Aplicadas')
                                            ->rows(2),
                                        Textarea::make('verification')
                                            ->label('Verificação / Eficácia')
                                            ->rows(2),
                                    ]),
                            ];
                        }
                        return [];
                    })
                    ->action(function ($data, $record) {
                        if ($record->product_type === 'Sebo') {
                            $record->tallowCertificates()->updateOrCreate(
                                ['sale_id' => $record->id],
                                array_merge($data, ['client_id' => $record->client_id])
                            );
                        } elseif ($record->product_type === 'Farinha') {
                            $record->mealCertificates()->updateOrCreate(
                                ['sale_id' => $record->id],
                                array_merge($data, ['client_id' => $record->client_id])
                            );
                        }
                        
                        return redirect()->to(route('sales.certificate.pdf', ['sale' => $record->id]));
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
