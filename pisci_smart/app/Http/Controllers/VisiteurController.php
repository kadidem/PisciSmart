<?php

namespace App\Http\Controllers;

use App\Models\Visiteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class VisiteurController extends Controller
{
    //afficher tous les visiteurs
    public function get_all_visiteur(){
        $visiteur=Visiteur::all();
        return response()->json( $visiteur);
    }


    //Afficher un visiteur
    public function getVisiteurById($id)
{
    try {
        // Rechercher le visiteur par ID
        $visiteur = Visiteur::find($id);

        // Si le visiteur n'existe pas, retourner une erreur
        if (!$visiteur) {
            return response()->json([
                'status' => 'error',
                'message' => 'Visiteur non trouvé'
            ], 404);
        }

        // Retourner le visiteur trouvé
        return response()->json([
            'status' => 'success',
            'data' => $visiteur
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Une erreur s/est produite lors de la récupération du visiteur.',
            'error' => $e->getMessage()
        ], 500);
    }
}

        //creer un nouveau visiteur
    public function create_visiteur(Request $request)
    {
        try {
            // Valider les données de la requête
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|string|unique:pisciculteurs|max:255', // Validation d'unicité
                'adresse' => 'required|string|max:255',
                'password' => 'required|string|min:8', // Assurez-vous d'ajouter le mot de passe
            ]);

            // Ajouter le mot de passe haché
            $validated['password'] = Hash::make($validated['password']);

            // Créer un nouveau visiteur
            $newvisiteur = Visiteur::create($validated);

            // Retourner une réponse JSON avec le pisciculteur créé
            return response()->json($newvisiteur, 201);

        } catch (ValidationException $e) {
            // Capturer l'exception de validation
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Gérer les autres exceptions
            return response()->json(['error' => 'Une erreur est survenue'], 500);
        }
    }

         //supprimer un visiteur
    public function delete_visiteur($id)
    {
        $visiteur = Visiteur::find($id);
        if ($visiteur) {
            $visiteur->delete();
            $res = [
                "message" => "Supprimé avec succès",
                "status" => 200,
                "data" => $visiteur,
            ];
        } else {
            $res = [
                "message" => "visiteur non trouvée",
                "status" => 404,
            ];
        }
        return response()->json($res);
    }

    //modifier visiteur
    public function update_visiteur(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
        ]);
        $visiteur= Visiteur::find($id);

        if ($visiteur) {
            $visiteur->update($validated);

            $res = [
                "message" => "Mise à jour réussie",
                "status" => 200,
                "data" => $visiteur,
            ];
        } else {
            $res = [
                "message" => "Visiteur non trouvée",
                "status" => 404,
            ];
        }

        return response()->json($res);
    }
}
