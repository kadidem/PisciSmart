<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    use HasFactory;
    protected $fillable = [
        'NumCycle',
        'AgePoisson',
        'NbrePoisson',
        'Espece',
        'DateDebut',
        'DateFin'

    ];
    public function ventes()
    {
        return $this->hasMany(Vente::class, 'id');
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class, 'id');
    }

    public function pertes()
    {
        return $this->hasMany(Perte::class, 'id');
    }

}
