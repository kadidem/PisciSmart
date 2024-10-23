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
    /*public function index()
    {
        $posts = Post::with(['pisciculteur', 'visiteur', 'typeDemande', 'commentaires', 'media'])
            ->get()
            ->each(function ($post) {
                $post->formatted_time = Carbon::parse($post->created_at)->diffForHumans();
                $post->makeHidden(['created_at', 'updated_at']);
            });

        return response()->json(['posts' => $posts], 200);
    }*/

    // Récupérer tous les posts
    public function index()
    {
        // Récupérer les posts avec les relations nécessaires
        $posts = Post::with(['user']) // Charger les relations 'user', 'commentaires', et 'media'
            ->get()
            ->each(function ($post) {
                // Ajouter le champ 'formatted_time' pour chaque post
                $post->formatted_time = Carbon::parse($post->created_at)->diffForHumans();
                // Masquer les champs 'created_at' et 'updated_at' dans la réponse
                $post->makeHidden(['created_at', 'updated_at']);
            });

        // Retourner la réponse avec les posts
        return response()->json(['posts' => $posts], 200);
    }



    //créer un post
    /*public function store(Request $request)
    {
        // Valider les données
        $validatedData = $request->validate([
            'contenu' => 'required|string',
            'type' => 'required|string', // Ajout de la validation pour le champ type
        ]);

        // Vérifier que le champ user_id est présent
        if (!$request->user_id) {
            return response()->json(['message' => 'L\'ID de l\'utilisateur est requis.'], 400);
        }

        // Créer le post
        $post = Post::create(array_merge($validatedData, ['user_id' => $request->user_id]));

        // Formatage du temps de création
        $post->formatted_time = Carbon::parse($post->created_at)->diffForHumans();

        // Retirer les champs created_at et updated_at de la réponse
        $post->makeHidden(['created_at', 'updated_at']);

        // Retourner la réponse JSON
        return response()->json([
            'message' => 'Post créé avec succès.',
            'post' => [
                'idPost' => $post->idPost,  // Utilisez le champ idPost de votre modèle
                'contenu' => $post->contenu,
                'type' => $post->type,
                'user_id' => $post->user_id,
                'formatted_time' => $post->formatted_time,
            ]
        ], 201);
    }*/

    public function store(Request $request)
    {
        // Valider les données
        $validatedData = $request->validate([
            'contenu' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
            'type' => 'required|string', // "vente", "recherche", etc.
            'user_id' => 'required|exists:users,id'
        ]);

        // Vérifier qu'au moins un des deux (contenu ou image) est fourni
        if (!$request->contenu && !$request->hasFile('image')) {
            return response()->json(['message' => 'Le contenu ou l\'image est requis.'], 400);
        }

        // Initialiser les données du post
        $postData = [
            'type' => $request->type,
            'user_id' => $request->user_id,
        ];

        // Si le contenu texte est présent
        if ($request->filled('contenu')) {
            $postData['contenu'] = $request->contenu;
        }

        // Si une image est uploadée
        if ($request->hasFile('image')) {
            // Stocker l'image dans un répertoire
            $imagePath = $request->file('image')->store('public/images'); // Stocke l'image dans le répertoire public
            $postData['image'] = $imagePath;
        }

        // Créer le post avec les données
        $post = Post::create($postData);

        // Formater l'heure de création
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


    //liste des post par user
    public function getPostsByUser(Request $request)
    {
        // Récupérer l'ID de l'utilisateur depuis la requête
        $userId = $request->query('user_id');

        // Vérifier si l'ID de l'utilisateur est fourni
        if (!$userId) {
            return response()->json(['message' => 'ID de l\'utilisateur requis.'], 400);
        }

        // Récupérer les posts par ID de l'utilisateur
        $posts = Post::where('user_id', $userId);
        // ->with(['commentaires', 'media']) // Notez que les relations avec pisciculteur et visiteur sont retirées
        //->get();

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
