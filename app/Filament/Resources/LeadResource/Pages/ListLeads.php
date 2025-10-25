<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kanban')
                ->label('Visualização Kanban')
                ->icon('heroicon-o-view-columns')
                ->url(fn (): string => static::$resource::getUrl('kanban')),
            Actions\CreateAction::make(),
        ];
    }
}
