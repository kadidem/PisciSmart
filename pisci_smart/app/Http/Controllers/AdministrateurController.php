<?php

namespace App\Http\Controllers;

use App\Models\Administrateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdministrateurController extends Controller
{
    // Afficher tous les administrateurs
    public function get_all_admin()
    {
        $administrateurs = Administrateur::all();
        return response()->json($administrateurs);
    }



    public function getAdministrateurById($id)
{
    try {
        // Rechercher l'administrateur par ID
        $administrateur = Administrateur::find($id);

        // Si l'administrateur'n'existe pas, retourner une erreur
        if (!$administrateur) {
            return response()->json([
                'status' => 'error',
                'message' => 'Administrateur non trouvé'
            ], 404);
        }

        // Retourner le administrateur trouvé
        return response()->json([
            'status' => 'success',
            'data' => $administrateur
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Une erreur s/est produite lors de la récupération de l/administrateur.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // Ajouter un nouvel administrateur
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:administrateurs,email',
        ]);

        // Vérifier si la validation a échoué
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Création du nouvel administrateur
        $administrateur = Administrateur::create($request->all());

        return response()->json([
            'message' => 'Administrateur ajouté avec succès',
            'data' => $administrateur
        ], 201);
    }


    // Supprimer un administrateur par ID
    public function destroy($idAdmi)
    {
        $administrateur = Administrateur::find($idAdmi);

        // Vérifier si l'administrateur existe
        if (!$administrateur) {
            return response()->json([
                'message' => 'Administrateur non trouvé'
            ], 404);
        }

        // Supprimer l'administrateur
        $administrateur->delete();

        return response()->json([
            'message' => 'Administrateur supprimé avec succès'
        ], 200);
    }


    // Mettre à jour un administrateur par ID
    public function update(Request $request, $idAdmi)
    {
        $administrateur = Administrateur::find($idAdmi);

        // Vérifier si l'administrateur existe
        if (!$administrateur) {
            return response()->json([
                'message' => 'Administrateur non trouvé'
            ], 404);
        }

        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:administrateurs,email,' . $idAdmi . ',idAdmi',
        ]);

        // Vérifier si la validation a échoué
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Mise à jour des données
        $administrateur->update($request->all());

        return response()->json([
            'message' => 'Administrateur mis à jour avec succès',
            'data' => $administrateur
        ], 200);
    }

}
