<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\PipelineStage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ConversionRateWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Lead::query();
        
        if (Auth::user()->isVendedor()) {
            $query->where('user_id', Auth::id());
        }
        
        $totalLeads = $query->count();
        
        $closedWonStage = PipelineStage::where('name', 'like', '%Fechado Ganho%')
            ->orWhere('name', 'like', '%Ganho%')
            ->first();
        
        $wonLeads = 0;
        if ($closedWonStage) {
            $wonLeads = (clone $query)->where('pipeline_stage_id', $closedWonStage->id)->count();
        }
        
        $conversionRate = $totalLeads > 0 ? ($wonLeads / $totalLeads) * 100 : 0;
        
        $closedLostStage = PipelineStage::where('name', 'like', '%Fechado Perdido%')
            ->orWhere('name', 'like', '%Perdido%')
            ->first();
        
        $lostLeads = 0;
        if ($closedLostStage) {
            $lostLeads = (clone $query)->where('pipeline_stage_id', $closedLostStage->id)->count();
        }
        
        return [
            Stat::make('Taxa de Conversão', number_format($conversionRate, 1) . '%')
                ->description("$wonLeads ganhos de $totalLeads leads")
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),
            
            Stat::make('Leads Perdidos', $lostLeads)
                ->description('Oportunidades não convertidas')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
