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
        $request->validate([
            'role' => 'required|in:pisciculteur,visiteur,employe',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => 'required|unique:pisciculteurs,telephone|unique:visiteurs,telephone|unique:employes,telephone',
            'password' => 'required|confirmed|min:6',
            // 'idDispo' => 'required_if:role,pisciculteur|exists:dispositifs,idDispo',
            'idPisciculteur' => 'required_if:role,employe|exists:pisciculteurs,idPisciculteur',
        ]);

        $data = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'password' => Hash::make($request->password),
        ];

        if ($request->role === 'pisciculteur') {
            // $data['idDispo'] = $request->idDispo;
            $user = Pisciculteur::create($data);
        } elseif ($request->role === 'visiteur') {
            $user = Visiteur::create($data);
        } elseif ($request->role === 'employe') {
            $data['idPisciculteur'] = $request->idPisciculteur;
            $user = Employe::create($data);
        }

        return response()->json(['message' => 'Utilisateur créé avec succès'], 201);
    }

    // Connexion
    public function login(Request $request)
    {
        $request->validate([
            'telephone' => 'required',
            'password' => 'required',
            'role' => 'required|in:pisciculteur,visiteur,employe',
        ]);

        if ($request->role === 'pisciculteur') {
            $user = Pisciculteur::where('telephone', $request->telephone)->first();
        } elseif ($request->role === 'visiteur') {
            $user = Visiteur::where('telephone', $request->telephone)->first();
        } elseif ($request->role === 'employe') {
            $user = Employe::where('telephone', $request->telephone)->first();
        }
         // Ajoutez cette ligne pour voir si un utilisateur est trouvé
    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'telephone' => ['Les informations d’identification fournies sont incorrectes.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Déconnexion
    // public function logout(Request $request)
    // {
    //    // Supprimer le token actif de l'utilisateur
    //   $request->user()->currentAccessToken()->delete();

    //  return response()->json([
    //      'message' => 'Déconnecté avec succès',
    //  ]);
    // }

    public function logout(Request $request)
    {

        // Supprimer le token actif de l'utilisateur
        switch ($request->role) {
            case 'pisciculteur':
                $user = Pisciculteur::where('telephone', $request->telephone)->first();
                break;
            case 'employe':
                $user = Employe::where('telephone', $request->telephone)->first();
                break;
            default:
                $user = Visiteur::where('telephone', $request->telephone)->first();
                break;
        }
        $user->tokens()->delete();

        return [
            'message' => 'Déconnecté avec succès',
        ];
    }

}

