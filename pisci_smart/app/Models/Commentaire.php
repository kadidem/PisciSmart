<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Commentaire extends Model
{
    protected $table = 'commentaires'; // Nom de la table
    protected $primaryKey = 'idCommentaire'; // Clé primaire
    public $timestamps = true;

    protected $fillable = [
        'idPost',
        'idPisciculteur',
        'idVisiteur',
        'contenu',
        'parent_id'
    ];

    // Attributs cachés
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Accesseur pour formatted_time
    protected $appends = ['formatted_time'];

    // Définir les relations
    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost', 'idPost');
    }

    public function pisciculteur()
    {
        return $this->belongsTo(Pisciculteur::class, 'idPisciculteur', 'idPisciculteur');
    }

    public function visiteur()
    {
        return $this->belongsTo(Visiteur::class, 'idVisiteur', 'idVisiteur');
    }

    // Relation pour le commentaire parent
    public function parent()
    {
        return $this->belongsTo(Commentaire::class, 'parent_id');
    }

    // Relation pour les commentaires enfants
    public function replies()
    {
        return $this->hasMany(Commentaire::class, 'parent_id');
    }

    // Accesseur pour formatted_time
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}

