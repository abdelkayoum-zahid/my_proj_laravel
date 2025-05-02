<?php

namespace Database\Seeders;

use App\Models\Emprunt;
use App\Models\User;
use App\Models\Livre;
use Illuminate\Database\Seeder;

class EmpruntSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des emprunts pour les utilisateurs existants
        $users = User::where('role', 'etudiant')->get();
        $livres = Livre::all();

        foreach ($users as $user) {
            // Chaque étudiant aura entre 0 et 3 emprunts
            $nbEmprunts = rand(0, 3);
            
            for ($i = 0; $i < $nbEmprunts; $i++) {
                // Sélectionner un livre disponible aléatoirement
                $livre = $livres->where('disponible', true)->random();
                
                // Créer l'emprunt avec une probabilité variée d'états
                $random = rand(1, 100);
                if ($random <= 60) { // 60% en cours
                    Emprunt::factory()->inProgress()->create([
                        'user_id' => $user->id,
                        'livre_id' => $livre->id,
                    ]);
                } elseif ($random <= 80) { // 20% retournés
                    Emprunt::factory()->returned()->create([
                        'user_id' => $user->id,
                        'livre_id' => $livre->id,
                    ]);
                } else { // 20% en retard
                    Emprunt::factory()->late()->create([
                        'user_id' => $user->id,
                        'livre_id' => $livre->id,
                    ]);
                }
                
                // Marquer le livre comme non disponible si l'emprunt est en cours ou en retard
                if ($random <= 60 || $random > 80) {
                    $livre->update(['disponible' => false]);
                }
            }
        }
    }
}
