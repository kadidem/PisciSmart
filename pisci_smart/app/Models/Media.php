<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    // Attributs qui peuvent être assignés en masse
    protected $fillable = [
        'idPost', // Clé étrangère vers Post
        'path',    // Chemin du fichier média


    ];
    protected $primaryKey='idMedia';

    /**
     * Relation inverse : Un média appartient à un post.
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
    }
}

