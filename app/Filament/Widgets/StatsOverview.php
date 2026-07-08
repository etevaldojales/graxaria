<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalCollected = \App\Models\Collection::where('status', 'Coletada')->sum('weight');
        $totalCost = \App\Models\Collection::where('status', 'Coletada')->sum('total_cost');
        
        $totalProduced = \App\Models\Batch::where('status', 'Concluído')->sum('output_tallow_weight') 
            + \App\Models\Batch::where('status', 'Concluído')->sum('output_meal_weight');
            
        $totalSales = \App\Models\Sale::where('status', 'Pago')->sum('total_value');

        return [
            Stat::make('Matéria-Prima Coletada', number_format($totalCollected, 2, ',', '.') . ' kg')
                ->description('Total de resíduos coletados e processados')
                ->descriptionIcon('heroicon-m-truck')
                ->color('success'),
            Stat::make('Custo de Coletas', 'R$ ' . number_format($totalCost, 2, ',', '.'))
                ->description('Total pago aos fornecedores')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
            Stat::make('Produção Acumulada', number_format($totalProduced, 2, ',', '.') . ' kg')
                ->description('Sebo e farinha produzidos (lotes concluídos)')
                ->descriptionIcon('heroicon-m-cpu-chip')
                ->color('info'),
            Stat::make('Faturamento de Vendas', 'R$ ' . number_format($totalSales, 2, ',', '.'))
                ->description('Subprodutos vendidos e pagos')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
