<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Activity;
use App\Models\Opportunity;
use App\Models\User;
use App\Models\PipelineStage;
use App\Models\Product;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $vendedores = User::where('role', 'vendedor')->get();
        $stages = PipelineStage::all();
        $products = Product::all();

        // Criar 50 leads
        foreach (range(1, 50) as $index) {
            $lead = Lead::create([
                'contact_name' => fake()->name(),
                'company' => fake()->company(),
                'email' => fake()->unique()->companyEmail(),
                'phone' => fake()->phoneNumber(),
                'source' => fake()->randomElement(['website', 'referral', 'social_media', 'cold_call', 'email_campaign', 'event', 'other']),
                'user_id' => $vendedores->random()->id,
                'pipeline_stage_id' => $stages->random()->id,
                'needs_summary' => fake()->paragraph(),
                'first_contact_date' => fake()->dateTimeBetween('-3 months', 'now'),
            ]);

            // Criar 1-3 atividades para cada lead
            $activityCount = rand(1, 3);
            foreach (range(1, $activityCount) as $i) {
                Activity::create([
                    'lead_id' => $lead->id,
                    'user_id' => $lead->user_id,
                    'type' => fake()->randomElement(['call', 'meeting', 'message', 'email']),
                    'scheduled_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
                    'notes' => fake()->paragraph(),
                    'status' => fake()->randomElement(['scheduled', 'completed', 'cancelled']),
                ]);
            }

            // 70% dos leads terão oportunidades
            if (rand(1, 10) <= 7) {
                $opportunityCount = rand(1, 2);
                foreach (range(1, $opportunityCount) as $i) {
                    $product = $products->random();
                    $quantity = rand(1, 5);
                    
                    Opportunity::create([
                        'lead_id' => $lead->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'estimated_value' => $product->price * $quantity,
                        'notes' => fake()->sentence(),
                    ]);
                }
            }
        }

        // Criar algumas atividades atrasadas propositalmente
        $recentLeads = Lead::take(10)->get();
        foreach ($recentLeads as $lead) {
            Activity::create([
                'lead_id' => $lead->id,
                'user_id' => $lead->user_id,
                'type' => 'call',
                'scheduled_at' => now()->subDays(rand(1, 5)),
                'notes' => 'Atividade atrasada para teste',
                'status' => 'scheduled',
            ]);
        }
    }
}
