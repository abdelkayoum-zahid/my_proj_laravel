<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Livre>
 */
class LivreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Roman', 'Science-fiction', 'Policier', 'Biographie', 'Histoire', 'Jeunesse', 'Technique'];
        
        return [
            'titre' => fake()->sentence(3),
            'auteur' => fake()->name(),
            'annee' => fake()->year(),
            'categorie' => fake()->randomElement($categories),
            'disponible' => fake()->boolean(80), // 80% de chance d'être disponible
        ];
    }
}
