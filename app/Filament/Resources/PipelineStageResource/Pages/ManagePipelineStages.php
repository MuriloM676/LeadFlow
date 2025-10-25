<?php

namespace App\Filament\Resources\PipelineStageResource\Pages;

use App\Filament\Resources\PipelineStageResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePipelineStages extends ManageRecords
{
    protected static string $resource = PipelineStageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
