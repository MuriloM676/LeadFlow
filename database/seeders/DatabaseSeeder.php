<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PipelineStage;
use App\Models\Product;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\Opportunity;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PipelineStageSeeder::class,
            ProductSeeder::class,
            LeadSeeder::class,
        ]);
    }
}
