<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    use HasFactory;
    protected $primaryKey = 'idCycle'; // Spécifie la clé primaire
    public $timestamps = false;
    protected $fillable = [
        'AgePoisson',
        'NbrePoisson',
        'DateDebut',
        'DateFin',
        'NumCycle',
        'espece',
    ];

    // Calculer automatiquement la date de fin en ajoutant une durée fixe (exemple : 6 mois)
    public static function boot()
    {
        parent::boot();

        static::creating(function ($cycle) {
            // Validation que la date de début ne soit pas dans le futur
            if (Carbon::parse($cycle->DateDebut)->isFuture()) {
                throw new \Exception("La date de début ne peut pas être dans le futur.");
            }

            // Calcul de la date de fin (par exemple 6 mois après la date de début)
            $cycle->DateFin = Carbon::parse($cycle->DateDebut)->addMonths(6);
        });
    }
}
