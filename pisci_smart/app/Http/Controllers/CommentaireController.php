<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Pisciculteur;
use App\Models\Visiteur;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CommentaireController extends Controller
{
    /**
     * Récupérer le nombre total de commentaires.
     */
    public function getTotalCommentaires()
    {
        $totalCommentaires = Commentaire::count();

        return response()->json(['total_commentaires' => $totalCommentaires]);
    }

    // Méthode pour afficher tous les commentaires
    public function index()
    {
        $commentaires = Commentaire::with(['post', 'pisciculteur', 'visiteur'])->get();

        // Ajouter le formatted_time et masquer created_at et updated_at
        $commentaires->each(function ($commentaire) {
            $commentaire->formatted_time = Carbon::parse($commentaire->created_at)->diffForHumans();
            $commentaire->makeHidden(['created_at', 'updated_at']);
        });

        return response()->json($commentaires);
    }

    // Méthode pour afficher un commentaire spécifique
    public function show($id)
    {
        $commentaire = Commentaire::find($id);

        if (!$commentaire) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        // Ajouter le formatted_time et masquer created_at et updated_at
        $commentaire->formatted_time = Carbon::parse($commentaire->created_at)->diffForHumans();
        $commentaire->makeHidden(['created_at', 'updated_at']);

        return response()->json($commentaire);
    }

    // Méthode pour créer un commentaire
    public function store(Request $request)
    {
        $request->validate([
            'idPost' => 'required|exists:posts,idPost',
            'idPisciculteur' => 'nullable|exists:pisciculteurs,idPisciculteur',
            'idVisiteur' => 'nullable|exists:visiteurs,idVisiteur',
            'contenu' => 'required|string',
        ]);

        if ($request->idPisciculteur && $request->idVisiteur) {
            return response()->json(['message' => 'Vous ne pouvez pas fournir à la fois un pisciculteur et un visiteur.'], 400);
        }

        if ($request->idPisciculteur && !Pisciculteur::find($request->idPisciculteur)) {
            return response()->json(['message' => 'Le pisciculteur spécifié n\'existe pas.'], 404);
        }

        if ($request->idVisiteur && !Visiteur::find($request->idVisiteur)) {
            return response()->json(['message' => 'Le visiteur spécifié n\'existe pas.'], 404);
        }

        try {
            $commentaire = Commentaire::create($request->all());

            // Ajouter le formatted_time et masquer created_at et updated_at
            $commentaire->formatted_time = Carbon::parse($commentaire->created_at)->diffForHumans();
            $commentaire->makeHidden(['created_at', 'updated_at']);

            return response()->json($commentaire, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue lors de la création du commentaire.'], 500);
        }
    }

    // Méthode pour mettre à jour un commentaire
    public function update(Request $request, $idCommentaire)
    {
        $request->validate([
            'idPost' => 'required|exists:posts,idPost',
            'idPisciculteur' => 'nullable|exists:pisciculteurs,idPisciculteur',
            'idVisiteur' => 'nullable|exists:visiteurs,idVisiteur',
            'contenu' => 'required|string',
        ]);

        if ($request->idPisciculteur && $request->idVisiteur) {
            return response()->json(['message' => 'Vous ne pouvez pas fournir à la fois un pisciculteur et un visiteur.'], 400);
        }

        if ($request->idPisciculteur && !Pisciculteur::find($request->idPisciculteur)) {
            return response()->json(['message' => 'Le pisciculteur spécifié n\'existe pas.'], 404);
        }

        if ($request->idVisiteur && !Visiteur::find($request->idVisiteur)) {
            return response()->json(['message' => 'Le visiteur spécifié n\'existe pas.'], 404);
        }

        $commentaire = Commentaire::find($idCommentaire);

        if (!$commentaire) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        $commentaire->update($request->all());

        // Ajouter le formatted_time et masquer created_at et updated_at
        $commentaire->formatted_time = Carbon::parse($commentaire->created_at)->diffForHumans();
        $commentaire->makeHidden(['created_at', 'updated_at']);

        return response()->json($commentaire);
    }

    // Méthode pour supprimer un commentaire
    public function destroy($idCommentaire)
    {
        $commentaire = Commentaire::find($idCommentaire);

        if (!$commentaire) {
            return response()->json(['message' => 'Commentaire non trouvé'], 404);
        }

        $commentaire->delete();

        return response()->json(['message' => 'Commentaire supprimé avec succès!']);
        
    }
}




