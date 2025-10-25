<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PipelineValueWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Lead::query()
            ->join('opportunities', 'leads.id', '=', 'opportunities.lead_id');
        
        if (Auth::user()->isVendedor()) {
            $query->where('leads.user_id', Auth::id());
        }
        
        $totalValue = $query->sum('opportunities.estimated_value');
        
        $averageValue = $query->count() > 0 
            ? $query->sum('opportunities.estimated_value') / $query->distinct('leads.id')->count()
            : 0;
        
        return [
            Stat::make('Valor Total do Pipeline', 'R$ ' . number_format($totalValue, 2, ',', '.'))
                ->description('Soma de todas as oportunidades')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            
            Stat::make('Valor Médio por Lead', 'R$ ' . number_format($averageValue, 2, ',', '.'))
                ->description('Média de valor por lead')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'),
        ];
    }
}
