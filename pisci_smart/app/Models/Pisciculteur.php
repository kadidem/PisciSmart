<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Pisciculteur extends Authenticatable
{
    use HasFactory, HasApiTokens;

    // Définir les champs que vous pouvez remplir en masse
    protected $fillable = ['nom', 'prenom', 'telephone', 'password', 'user_id', 'idDispo'];

    // Définir la clé primaire
    protected $primaryKey = 'idPisciculteur';

    // Activer les timestamps si vous les utilisez
    public $timestamps = false;

    // Masquer les champs sensibles
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relation avec le modèle `User`
     * Un pisciculteur appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relation avec le modèle `Dispositif`
     * Un pisciculteur peut être lié à un dispositif.
     */
    public function dispositif()
    {
        return $this->belongsTo(Dispositif::class, 'idDispo', 'idDispo');
    }
}

