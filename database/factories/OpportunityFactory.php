<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpportunityFactory extends Factory
{
    protected $model = Opportunity::class;

    public function definition(): array
    {
        return [
            'lead_id' => Lead::factory(),
            'product_id' => Product::factory(),
            'quantity' => fake()->numberBetween(1, 10),
            'estimated_value' => fake()->randomFloat(2, 500, 50000),
            'notes' => fake()->sentence(),
        ];
    }
}
