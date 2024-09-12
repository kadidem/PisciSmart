<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bassin extends Model
{
    use HasFactory;

    // Les attributs qui peuvent être assignés en masse
    protected $fillable = ['nomBassin', 'dimension', 'description', 'unite', 'idDispo', 'date'];

    // Spécifier la clé primaire si elle n'est da,n pas 'id'
    protected $primaryKey = 'idBassin';

    // Désactiver les timestamps si vous ne les utilisez pas
    public $timestamps = false;
}
