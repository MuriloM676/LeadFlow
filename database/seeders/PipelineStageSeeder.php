<?php

namespace Database\Seeders;

use App\Models\PipelineStage;
use Illuminate\Database\Seeder;

class PipelineStageSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            ['name' => 'Novo', 'order' => 1, 'color' => '#3b82f6'],
            ['name' => 'Contato Feito', 'order' => 2, 'color' => '#8b5cf6'],
            ['name' => 'Proposta Enviada', 'order' => 3, 'color' => '#f59e0b'],
            ['name' => 'Negociação', 'order' => 4, 'color' => '#ec4899'],
            ['name' => 'Fechado Ganho', 'order' => 5, 'color' => '#10b981'],
            ['name' => 'Fechado Perdido', 'order' => 6, 'color' => '#ef4444'],
        ];

        foreach ($stages as $stage) {
            PipelineStage::create($stage);
        }
    }
}
