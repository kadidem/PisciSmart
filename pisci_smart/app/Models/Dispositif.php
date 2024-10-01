<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispositif extends Model
{
    use HasFactory;
    protected $fillable = ['num', 'longitude', 'latitude', 'numero_serie'];
    protected $primaryKey = 'idDispo';

    public $timestamps = false;

     // Relation avec le modèle Pisciculteur
     public function pisciculteur()
     {
         return $this->belongsTo(Pisciculteur::class, 'idPisciculteur');
     }

}
