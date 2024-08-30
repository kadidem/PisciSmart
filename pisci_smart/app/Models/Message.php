<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    protected $fillable = [
        'expediteur_pisciculteur_id',
        'expediteur_visiteur_id',
        'destinataire_pisciculteur_id',
        'destinataire_visiteur_id',
        'contenu',
        'lu'
    ];

    protected $primaryKey = 'idMessage';

    // Ajouter un accessor pour le temps formaté
    protected $appends = ['formatted_time'];

    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function expediteurPisciculteur()
    {
        return $this->belongsTo(Pisciculteur::class, 'expediteur_pisciculteur_id', 'idPisciculteur');
    }

    public function expediteurVisiteur()
    {
        return $this->belongsTo(Visiteur::class, 'expediteur_visiteur_id', 'idVisiteur');
    }

    public function destinatairePisciculteur()
    {
        return $this->belongsTo(Pisciculteur::class, 'destinataire_pisciculteur_id', 'idPisciculteur');
    }

    public function destinataireVisiteur()
    {
        return $this->belongsTo(Visiteur::class, 'destinataire_visiteur_id', 'idVisiteur');
    }

    // Optionnel : Exclure les attributs inutiles dans la réponse JSON
    protected $hidden = ['created_at', 'updated_at'];
}


