<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nourriture extends Model
{
    protected $table = 'nourritures';
    protected $primaryKey = 'idNourriture';
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'quantite',
        'date',
        'heure',
        'idCycle'
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d', // Format pour la date
    ];

    // Accessor pour formater l'heure
    public function getHeureAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i:s');
    }

    // Mutator pour sauvegarder l'heure
    public function setHeureAttribute($value)
    {
        $this->attributes['heure'] = \Carbon\Carbon::createFromFormat('H:i:s', $value)->format('H:i:s');
    }
}
