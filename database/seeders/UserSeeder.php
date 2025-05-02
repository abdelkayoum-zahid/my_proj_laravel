<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un admin par défaut
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'role' => 'admin',
            'is_approved' => true,
        ]);

        // Créer un gestionnaire par défaut
        User::create([
            'name' => 'Gestionnaire',
            'email' => 'gestionnaire@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'role' => 'gestionnaire',
            'is_approved' => true,
        ]);

        // Créer 10 étudiants
        User::factory()->count(10)->create([
            'role' => 'etudiant',
            'is_approved' => true,
        ]);

        // Créer 3 étudiants en attente d'approbation
        User::factory()->count(3)->create([
            'role' => 'etudiant',
            'is_approved' => false,
        ]);
    }
}