<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['idPisciculteur', 'idVisiteur', 'idTypeDemande', 'contenu'];
    protected $primaryKey = 'idPost';

    public $incrementing = true;
    protected $keyType = 'int';

    // Charger automatiquement les relations
    protected $with = ['pisciculteur', 'visiteur', 'typeDemande', 'commentaires', 'media'];

    // Formater l'heure de création
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    // Relations
    public function pisciculteur()
    {
        return $this->belongsTo(Pisciculteur::class, 'idPisciculteur');
    }

    public function visiteur()
    {
        return $this->belongsTo(Visiteur::class, 'idVisiteur');
    }

    public function typeDemande()
    {
        return $this->belongsTo(TypeDemande::class, 'idTypeDemande');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'idPost');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'idPost');
    }
}
