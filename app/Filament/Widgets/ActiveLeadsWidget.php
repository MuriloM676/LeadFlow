<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ActiveLeadsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Lead::query();
        
        if (Auth::user()->isVendedor()) {
            $query->where('user_id', Auth::id());
        }
        
        $totalLeads = $query->count();
        $newLeadsThisMonth = (clone $query)->whereMonth('created_at', now()->month)->count();
        
        return [
            Stat::make('Total de Leads Ativos', $totalLeads)
                ->description('Leads no pipeline')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart([7, 12, 15, 18, 22, 28, $totalLeads]),
            
            Stat::make('Novos este mês', $newLeadsThisMonth)
                ->description('Leads criados em ' . now()->format('F'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
