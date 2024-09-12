<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

    protected $table = 'likes'; // Nom de la table
    protected $primaryKey = 'idLike'; // Clé primaire
    // Attributs qui peuvent être assignés en masse
    protected $fillable = [
        'idPost', // Clé étrangère vers Post
        'idPisciculteur', // Clé étrangère vers Pisciculteur
        'idVisiteur', // Clé étrangère vers Visiteur
    ];

    /**
     * Relation inverse : Un like appartient à un post.
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
    }

    /**
     * Relation inverse : Un like peut appartenir à un pisciculteur.
     */
    public function pisciculteur()
    {
        return $this->belongsTo(Pisciculteur::class, 'idPisciculteur');
    }

    /**
     * Relation inverse : Un like peut appartenir à un visiteur.
     */
    public function visiteur()
    {
        return $this->belongsTo(Visiteur::class, 'idVisiteur');
    }

    /**
     * Ajouter ou retirer un like.
     */
   /**
 * Ajouter ou retirer un like.
 */
public static function toggleLike($postId, $userId, $userType)
{
    $userColumn = $userType === 'pisciculteur' ? 'idPisciculteur' : 'idVisiteur';

    // Vérifier si le post existe
    if (!Post::find($postId)) {
        throw new \Exception('Le post spécifié n\'existe pas.');
    }

    // Vérifier si l'utilisateur existe
    if ($userType === 'pisciculteur' && !Pisciculteur::find($userId)) {
        throw new \Exception('Le pisciculteur spécifié n\'existe pas.');
    }

    if ($userType === 'visiteur' && !Visiteur::find($userId)) {
        throw new \Exception('Le visiteur spécifié n\'existe pas.');
    }

    // Vérifier si un like existe déjà
    $like = self::where('idPost', $postId)
                ->where($userColumn, $userId)
                ->first();

    if ($like) {
        // Si un like existe, le supprimer
        $like->delete();
    } else {
        // Sinon, en créer un nouveau
        self::create([
            'idPost' => $postId,
            $userColumn => $userId,
        ]);
    }


}

}


