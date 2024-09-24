<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    // Créer un nouveau média
    public function store(Request $request)
    {
        $request->validate([
            'idPost' => 'required|exists:posts,idPost',
            'path' => 'required|string',
        ]);

        $media = Media::create([
            'idPost' => $request->idPost,
            'path' => $request->path,
        ]);

        return response()->json(['message' => 'Média créé avec succès.', 'media' => $media], 201);
    }

    // Lire tous les médias d'un post
    public function index($postId)
    {
        $medias = Media::where('idPost', $postId)->get();

        return response()->json(['medias' => $medias]);
    }

    // Lire un média spécifique
    public function show($idMedia)
    {
        $media = Media::find($idMedia);

        if (!$media) {
            return response()->json(['message' => 'Le média avec cet ID n\'existe pas.'], 404);
        }

        return response()->json(['media' => $media]);
    }


    // Mettre à jour un média existant
    public function update(Request $request, $idMedia)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $media = Media::findOrFail($idMedia);
        $media->update([
            'path' => $request->path,
        ]);

        return response()->json(['message' => 'Média mis à jour avec succès.', 'media' => $media]);
    }

    // Supprimer un média
    public function destroy($idMedia)
    {
        $media = Media::find($idMedia);

        if (!$media) {
            return response()->json(['message' => 'Le média avec cet ID n\'existe pas.'], 404);
        }

        $media->delete();

        return response()->json(['message' => 'Média supprimé avec succès.']);
    }

}
