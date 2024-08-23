<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Pisciculteur extends Model
{
    use HasFactory;

    // Définir les champs que vous pouvez remplir en masse
    protected $fillable = ['nom', 'prenom', 'adresse', 'telephone', 'password'];

    // Définir la clé primaire
    protected $primaryKey = 'idPisciculteur';

    // Activer les timestamps si vous les utilisez
    public $timestamps = false;

    // Mutateur pour hacher le mot de passe avant de l'enregistrer
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}

