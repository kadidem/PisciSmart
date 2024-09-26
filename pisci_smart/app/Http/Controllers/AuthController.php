<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Pisciculteur;
use App\Models\Visiteur;
use App\Models\Employe;
use App\Models\User;

class AuthController extends Controller
{


    // Inscription
    public function register(Request $request)
    {
        // Valider les données de la requête
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => 'required|string',
            'password' => 'required|min:6',
            //'adresse' => 'required|string|max:255'kav
        ]);

        // Préparer les données
        $data = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            //'adresse' => $request->adresse,
            'password' => Hash::make($request->password),
        ];

        // Créer l'utilisateur dans la table `pisciculteurs` (par défaut)
        $user = User::create($data);

        return response()->json(['message' => 'Utilisateur créé avec succès'], 201);
    }


    // Connexion
   // Connexion
public function login(Request $request)
{
    // Valider les données de la requête
    $request->validate([
        'telephone' => 'required',
        'password' => 'required',
    ]);

    // Chercher l'utilisateur dans la table `users`
    $user = User::where('telephone', $request->telephone)->first();

    // Vérifier si l'utilisateur est trouvé
    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }

    // Vérifier le mot de passe
    if (Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Le mot de passe est incorrect.'], 401);
    }

    // Créer un token d'authentification
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Connexion réussie',
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => [
            'telephone' => $user->telephone,
            'prenom' => $user->prenom,
            'nom' => $user->nom
        ]
    ], 200);
}

    // Déconnexion
    public function logout(Request $request)
    {
        // Vérifier si l'utilisateur est authentifié
        if ($request->user()) {
            // Supprimer le token actif de l'utilisateur
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Déconnecté avec succès',
            ], 200);
        }

        return response()->json([
            'message' => 'Aucun utilisateur authentifié trouvé.',
        ], 401);
    }

    


}
