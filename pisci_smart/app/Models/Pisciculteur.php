<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Pisciculteur extends Authenticatable

{
    use HasFactory;
    use HasApiTokens;
    // Définir les champs que vous pouvez remplir en masse
    protected $fillable = ['nom', 'prenom', 'adresse', 'telephone', 'password','idDispo','status',];

    // Définir la clé primaire
    protected $primaryKey = 'idPisciculteur';

    // Activer les timestamps si vous les utilisez
    public $timestamps = false;


    protected $hidden = [
        'password',
        'remember_token',
    ];
}

