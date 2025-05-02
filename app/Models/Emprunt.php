<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Emprunt extends Model
{
    /** @use HasFactory<\Database\Factories\EmpruntFactory> */
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'livre_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour',
        'statut',
    ];

    protected $dates = [
        'date_emprunt',
        'date_retour_prevue',
        'date_retour',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function isEnRetard()
    {
        return !$this->date_retour && $this->date_retour_prevue && Carbon::parse($this->date_retour_prevue)->isPast();
    }

    public function isEnCours()
    {
        return !$this->date_retour;
    }

    public function isReturned()
    {
        return (bool) $this->date_retour;
    }
}
