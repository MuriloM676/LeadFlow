<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\PipelineStage;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class LeadsByStageChart extends ChartWidget
{
    protected static ?string $heading = 'Leads por Etapa do Pipeline';
    
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $stages = PipelineStage::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        $data = [];
        $labels = [];
        
        foreach ($stages as $stage) {
            $query = Lead::where('pipeline_stage_id', $stage->id);
            
            if (Auth::user()->isVendedor()) {
                $query->where('user_id', Auth::id());
            }
            
            $labels[] = $stage->name;
            $data[] = $query->count();
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Leads',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.5)',
                        'rgba(16, 185, 129, 0.5)',
                        'rgba(245, 158, 11, 0.5)',
                        'rgba(239, 68, 68, 0.5)',
                        'rgba(139, 92, 246, 0.5)',
                        'rgba(236, 72, 153, 0.5)',
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
