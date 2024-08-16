<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrateur extends Model
{
    use HasFactory;

    // Les champs qui peuvent être remplis en masse
    protected $fillable = ['nom', 'prenom', 'email'];

    // Spécifiez la clé primaire
    protected $primaryKey = 'idAdmi';

    // Désactivez les timestamps
    public $timestamps = false;
}

