<?php

namespace Database\Factories;

use App\Models\Livre;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Emprunt>
 */
class EmpruntFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date_emprunt = $this->faker->dateTimeBetween('-3 months', 'now');
        $date_retour_prevue = Carbon::parse($date_emprunt)->addDays(15);
        $est_retourne = $this->faker->boolean(30); // 30% de chance d'être retourné
        $date_retour = $est_retourne ? 
            $this->faker->dateTimeBetween($date_emprunt, 'now') : 
            null;

        // Déterminer le statut
        $statut = 'en_cours';
        if ($date_retour) {
            $statut = 'retourné';
        } elseif ($date_retour_prevue < now()) {
            $statut = 'en_retard';
        }

        return [
            'user_id' => User::factory(),
            'livre_id' => Livre::factory(),
            'date_emprunt' => $date_emprunt,
            'date_retour_prevue' => $date_retour_prevue,
            'date_retour' => $date_retour,
            'statut' => $statut,
        ];
    }

    /**
     * Indicate that the emprunt is returned.
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_retour' => $this->faker->dateTimeBetween($attributes['date_emprunt'], 'now'),
            'statut' => 'retourné',
        ]);
    }

    /**
     * Indicate that the emprunt is late.
     */
    public function late(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_retour_prevue' => Carbon::now()->subDays(rand(1, 30)),
            'date_retour' => null,
            'statut' => 'en_retard',
        ]);
    }

    /**
     * Indicate that the emprunt is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_retour' => null,
            'statut' => 'en_cours',
        ]);
    }
}
