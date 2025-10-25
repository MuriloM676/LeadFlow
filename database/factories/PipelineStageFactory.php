<?php

namespace Database\Factories;

use App\Models\PipelineStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PipelineStageFactory extends Factory
{
    protected $model = PipelineStage::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'order' => fake()->numberBetween(1, 10),
            'color' => fake()->hexColor(),
            'is_active' => true,
        ];
    }
}
