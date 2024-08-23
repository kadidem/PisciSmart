<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Pisciculteur;
use App\Models\Visiteur;
use App\Models\Employe;

class AuthController extends Controller
{
    // Inscription
    public function register(Request $request)
    {
        $request->validate([
            'role' => 'required|in:pisciculteur,visiteur,employe',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'telephone' => 'required|unique:pisciculteurs,telephone|unique:visiteurs,telephone|unique:employes,telephone',
            'password' => 'required|confirmed|min:6',
            'device_id' => 'required_if:role,pisciculteur|nullable',
            'idpisciculteur' => 'required_if:role,employe|nullable'
        ]);

        $data = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
        ];

        if ($request->role === 'pisciculteur') {
            $data['device_id'] = $request->device_id;
            $user = Pisciculteur::create($data);
        } elseif ($request->role === 'visiteur') {
            $user = Visiteur::create($data);
        } elseif ($request->role === 'employe') {
            $data['idpisciculteur'] = $request->idpisciculteur;
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
    public function logout(Request $request)
    {
        // Supprimer le token actif de l'utilisateur
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnecté avec succès',
        ]);
    }
}

