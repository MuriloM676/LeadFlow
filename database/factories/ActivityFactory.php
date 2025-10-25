<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'lead_id' => Lead::factory(),
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['call', 'meeting', 'message', 'email']),
            'scheduled_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'notes' => fake()->paragraph(),
            'status' => fake()->randomElement(['scheduled', 'completed', 'cancelled']),
        ];
    }
}
