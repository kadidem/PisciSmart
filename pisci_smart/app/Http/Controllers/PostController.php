<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\TypeDemande;

class PostController extends Controller
{
    // Récupérer tous les posts
    public function index()
    {
        $posts = Post::with(['pisciculteur', 'visiteur', 'typeDemande', 'commentaires', 'media'])
                    ->get()
                    ->each(function ($post) {
                        $post->formatted_time = Carbon::parse($post->created_at)->diffForHumans();
                        $post->makeHidden(['created_at', 'updated_at']);
                    });

        return response()->json(['posts' => $posts], 200);
    }


    // Méthode pour créer un post
public function store(Request $request)
{
    // Valider les données
    $validatedData = $request->validate([
        'idPisciculteur' => 'nullable|exists:pisciculteurs,idPisciculteur',
        'idVisiteur' => 'nullable|exists:visiteurs,idVisiteur',
        'idTypeDemande' => 'required|exists:type_demandes,idTypeDemande',
        'contenu' => 'required|string',
    ]);

    // Vérifier qu'au moins un des deux champs (idPisciculteur ou idVisiteur) est présent
    if (!$request->idPisciculteur && !$request->idVisiteur) {
        return response()->json(['message' => 'ID du pisciculteur ou du visiteur requis.'], 400);
    }

    // Vérifier que les deux champs ne sont pas présents en même temps
    if ($request->idPisciculteur && $request->idVisiteur) {
        return response()->json(['message' => 'Vous ne pouvez pas spécifier à la fois un pisciculteur et un visiteur.'], 400);
    }

    $post = Post::create($validatedData);

    $post->formatted_time = Carbon::parse($post->created_at)->diffForHumans();

    // Retirer les champs created_at et updated_at de la réponse
    $post->makeHidden(['created_at', 'updated_at']);

    // Retourner la réponse JSON
    return response()->json([
        'message' => 'Post créé avec succès.',
        'post' => $post
    ], 201);
}




     //rechercher un post par type demande (exple: achat de poissons, vente poisson...)
     public function filterByType(Request $request)
     {

         $typeDemandeId = $request->query('idTypeDemande');

         // Vérification si l'ID du type de demande est manquant
         if (!$typeDemandeId) {
             return response()->json(['message' => 'ID du type de demande requis.'], 400);
         }

         // Vérification si le type de demande existe
         $typeDemande = TypeDemande::find($typeDemandeId);
         if (!$typeDemande) {
             Log::warning('TypeDemande non trouvé pour ID: ' . $typeDemandeId);
             return response()->json(['message' => 'Type de demande non trouvé.'], 404);
         }

         // Ajout d'un log pour vérifier la valeur de typeDemandeId
         Log::info('ID Type de Demande: ' . $typeDemandeId);

         // Récupération des posts correspondant au type de demande
         $posts = Post::where('idTypeDemande', $typeDemandeId)
                      ->with(['pisciculteur', 'visiteur', 'typeDemande', 'commentaires', 'media'])
                      ->get();

         // Ajout d'un log pour vérifier le nombre de posts récupérés
         Log::info('Nombre de posts trouvés: ' . $posts->count());

         // Vérification si aucun post n'a été trouvé
         if ($posts->isEmpty()) {
             Log::warning('Aucun post trouvé pour idTypeDemande: ' . $typeDemandeId);
             return response()->json(['message' => 'Post non trouvé.'], 404);
         }

         // Formatage du temps pour chaque post et masquer des champs created_at et updated_at
         $posts->each(function ($post) {
             $post->formatted_time = Carbon::parse($post->created_at)->diffForHumans();
             $post->makeHidden(['created_at', 'updated_at']);
         });

         // Retourne la réponse avec les posts trouvés
         return response()->json(['posts' => $posts], 200);
     }


     //Récuperer les posts d'un utilisateur
     public function getPostsByUser(Request $request)
     {
         $pisciculteurId = $request->query('idPisciculteur');
         $visiteurId = $request->query('idVisiteur');

         // Vérifier si l'ID du pisciculteur ou du visiteur est fourni
         if (!$pisciculteurId && !$visiteurId) {
             return response()->json(['message' => 'ID du pisciculteur ou du visiteur requis.'], 400);
         }

         // Récupérer les posts par ID du pisciculteur ou du visiteur
         $query = Post::query();

         if ($pisciculteurId) {
             $query->where('idPisciculteur', $pisciculteurId);
         }

         if ($visiteurId) {
             $query->where('idVisiteur', $visiteurId);
         }

         $posts = $query->with(['pisciculteur', 'visiteur', 'typeDemande', 'commentaires', 'media'])->get();

         // Vérifier si aucun post n'a été trouvé
         if ($posts->isEmpty()) {
             return response()->json(['message' => 'Aucun post trouvé pour cet utilisateur.'], 404);
         }

         // Formatage du temps pour chaque post et masquage des champs created_at et updated_at
         $posts->each(function ($post) {
             $post->formatted_time = Carbon::parse($post->created_at)->diffForHumans();
             $post->makeHidden(['created_at', 'updated_at']);
         });

         return response()->json(['posts' => $posts], 200);
     }

     //pour supprimer un post
     public function deletePost($id)
{
    // Trouver le post par son ID
    $post = Post::find($id);

    // Vérifier si le post existe
    if (!$post) {
        return response()->json(['message' => 'Post non trouvé.'], 404);
    }

    // Supprimer le post
    $post->delete();

    // Retourner une réponse de succès
    return response()->json(['message' => 'Post supprimé avec succès.'], 200);
}

}
