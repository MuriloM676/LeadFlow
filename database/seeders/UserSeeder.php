<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Criar usuário gestor padrão
        User::create([
            'name' => 'Gestor Principal',
            'email' => 'gestor@example.com',
            'password' => Hash::make('password'),
            'role' => 'gestor',
            'email_verified_at' => now(),
        ]);

        // Criar usuários vendedores padrão
        User::create([
            'name' => 'João Vendedor',
            'email' => 'vendedor@example.com',
            'password' => Hash::make('password'),
            'role' => 'vendedor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Maria Silva',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'role' => 'vendedor',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pedro Santos',
            'email' => 'pedro@example.com',
            'password' => Hash::make('password'),
            'role' => 'vendedor',
            'email_verified_at' => now(),
        ]);

        // Criar mais vendedores aleatórios
        User::factory()->vendedor()->count(5)->create();
    }
}
