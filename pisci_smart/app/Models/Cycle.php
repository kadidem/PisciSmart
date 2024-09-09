<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cycle extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'idCycle';

    // Les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'AgePoisson',
        'NbrePoisson',
        'DateDebut',
        'DateFin',
        'NumCycle',
        'espece',
        'idBassin',
        'description' // Ajout de description ici
    ];

    // Méthode pour définir la date de fin
    public static function boot()
    {
        parent::boot();

        static::creating(function ($cycle) {
            // Calculer la date de fin en ajoutant 6 mois à la date de début
            $cycle->DateFin = Carbon::parse($cycle->DateDebut)->addMonths(6);
        });
    }

    // Les règles de validation pour ce modèle
    protected static $rules = [
        'DateDebut' => 'required|date|before_or_equal:today',
        'description' => 'nullable|string|max:255', // Ajout de la validation pour description
    ];
}
