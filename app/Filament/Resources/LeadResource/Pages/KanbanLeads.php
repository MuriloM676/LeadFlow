<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use App\Models\PipelineStage;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KanbanLeads extends Page
{
    protected static string $resource = LeadResource::class;

    protected static string $view = 'filament.resources.lead-resource.pages.kanban-leads';
    
    protected static ?string $title = 'Pipeline de Leads';

    public function getStages()
    {
        return PipelineStage::where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public function getLeadsByStage($stageId)
    {
        $query = Lead::where('pipeline_stage_id', $stageId)
            ->with(['user', 'opportunities']);
        
        // Se for vendedor, mostrar apenas seus leads
        if (Auth::user()->isVendedor()) {
            $query->where('user_id', Auth::id());
        }
        
        return $query->get();
    }

    public function updateLeadStage($leadId, $newStageId)
    {
        $lead = Lead::findOrFail($leadId);
        
        // Verificar permissão
        if (Auth::user()->isVendedor() && $lead->user_id !== Auth::id()) {
            return;
        }
        
        $lead->update(['pipeline_stage_id' => $newStageId]);
        
        $this->dispatch('lead-updated');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('list')
                ->label('Visualização em Lista')
                ->icon('heroicon-o-list-bullet')
                ->url(fn (): string => static::$resource::getUrl('index')),
            Actions\CreateAction::make()
                ->url(fn (): string => static::$resource::getUrl('create')),
        ];
    }
}
