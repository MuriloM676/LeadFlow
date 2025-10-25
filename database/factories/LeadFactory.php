<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'contact_name' => fake()->name(),
            'company' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'source' => fake()->randomElement(['website', 'referral', 'social_media', 'cold_call', 'email_campaign', 'event', 'other']),
            'user_id' => User::factory(),
            'pipeline_stage_id' => PipelineStage::factory(),
            'needs_summary' => fake()->paragraph(),
            'first_contact_date' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
