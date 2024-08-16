<?php
// app/Http/Controllers/Api/CommentController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        // Récupérer tous les commentaires pour un post spécifique
        return $post->comments()->with('user')->get();
    }

    public function store(Request $request, Post $post)
    {
        // Validation des données d'entrée
        $data = $request->validate([
            'content' => 'required|string',
        ]);

        // Attribution de l'utilisateur connecté au commentaire
        $data['user_id'] = auth()->id();

        // Créer le commentaire lié au post
        $comment = $post->comments()->create($data);

        // Retourner le commentaire créé avec un statut 201 (Created)
        return response()->json($comment, 201);
    }

    public function show(Comment $comment)
    {
        // Charger la relation utilisateur pour le commentaire
        return $comment->load('user');
    }

    public function destroy(Comment $comment)
    {
        // Suppression du commentaire
        $comment->delete();
        // Retourner une réponse vide avec un statut 204 (No Content)
        return response()->json(null, 204);
    }
}