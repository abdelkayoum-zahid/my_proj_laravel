<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    /** @use HasFactory<\Database\Factories\LivreFactory> */
    use HasFactory;
    protected $fillable = [
      'titre',
      'auteur',
      'annee',
      'categorie',
      'disponible',
  ];
  public function emprunts()
  {
      return $this->hasMany(Emprunt::class);
  }
}
