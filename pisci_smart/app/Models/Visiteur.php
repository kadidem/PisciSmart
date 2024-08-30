<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Visiteur extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    // Définir les champs que vous pouvez remplir en masse
    protected $fillable = ['nom', 'prenom', 'adresse', 'telephone', 'password'];

    // Définir la clé primaire
    protected $primaryKey = 'idVisiteur';

    // Activer les timestamps si vous les utilisez
    public $timestamps = false;

    // Mutateur pour hacher le mot de passe avant de l'enregistrer

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
