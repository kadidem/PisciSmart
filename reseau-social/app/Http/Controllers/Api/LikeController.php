<?php
// app/Http/Controllers/Api/LikeController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Post $post)
    {
        // Vérifier si l'utilisateur a déjà aimé ce post
        $like = Like::where('user_id', auth()->id())
            ->where('post_id', $post->id)
            ->first();

        if (!$like) {
            // Créer un nouveau like pour ce post
            $like = $post->likes()->create(['user_id' => auth()->id()]);
        }

        // Retourner le like créé avec un statut 201 (Created)
        return response()->json($like, 201);
    }

    public function destroy(Post $post)
    {
        // Supprimer le like de l'utilisateur pour ce post
        $like = Like::where('user_id', auth()->id())
            ->where('post_id', $post->id)
            ->first();

        if ($like) {
            $like->delete();
        }

        // Retourner une réponse vide avec un statut 204 (No Content)
        return response()->json(null, 204);
    }
}