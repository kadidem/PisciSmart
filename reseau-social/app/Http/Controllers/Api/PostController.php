<?php
// app/Http/Controllers/Api/PostController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // Récupérer tous les posts avec les relations utilisateur, commentaires et likes
        return Post::with(['user', 'comments', 'likes'])->get();
    }

    public function store(Request $request)
    {
        // Validation des données d'entrée
        $data = $request->validate([
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Gestion de l'upload d'image
        if ($request->has('image')) {
            $data['image_path'] = $request->file('image')->store('images', 'public');
        }

        // Attribution de l'utilisateur connecté au post
        $data['user_id'] = auth()->id();

        // Création du post
        $post = Post::create($data);

        // Retourner le post créé avec un statut 201 (Created)
        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        // Charger les relations pour le post (utilisateur, commentaires, likes)
        return $post->load(['user', 'comments', 'likes']);
    }

    public function destroy(Post $post)
    {
        // Suppression du post
        $post->delete();
        // Retourner une réponse vide avec un statut 204 (No Content)
        return response()->json(null, 204);
    }
}