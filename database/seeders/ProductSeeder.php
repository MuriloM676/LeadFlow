<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Consultoria Empresarial',
                'description' => 'Consultoria completa para gestão empresarial',
                'price' => 5000.00,
                'is_active' => true,
            ],
            [
                'name' => 'Software de Gestão',
                'description' => 'Sistema completo de gestão empresarial',
                'price' => 15000.00,
                'is_active' => true,
            ],
            [
                'name' => 'Treinamento de Equipe',
                'description' => 'Treinamento completo para equipes de vendas',
                'price' => 3000.00,
                'is_active' => true,
            ],
            [
                'name' => 'Marketing Digital',
                'description' => 'Pacote completo de marketing digital',
                'price' => 8000.00,
                'is_active' => true,
            ],
            [
                'name' => 'Desenvolvimento Web',
                'description' => 'Desenvolvimento de site institucional',
                'price' => 12000.00,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Criar mais produtos aleatórios
        Product::factory()->count(10)->create();
    }
}
