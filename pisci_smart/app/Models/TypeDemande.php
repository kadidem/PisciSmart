<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDemande extends Model
{
    use HasFactory;

    // Nom de la table (facultatif si le nom de la table est déjà correct)
    protected $table = 'type_demandes';

    // Nom de la clé primaire
    protected $primaryKey = 'idTypeDemande';

    // Si la clé primaire est auto-incrémentée
    public $incrementing = true;

    // Type de la clé primaire
    protected $keyType = 'int';

    // Désactiver les timestamps
    public $timestamps = false;

    protected $fillable = ['nom'];

    // Relation avec les posts
    public function posts()
    {
        return $this->hasMany(Post::class, 'idTypeDemande', 'idTypeDemande');
    }
}


