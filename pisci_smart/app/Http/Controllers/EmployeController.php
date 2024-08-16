<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Assure-toi que cette ligne est présente
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    // Afficher toutes les employes
    public function get_all_employe()
    {
        $employe = Employe::all();
        return response()->json($employe);
    }

    // Créer un employé
    public function create_employe(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
                'idPisciculteur' => 'required|integer|exists:pisciculteurs,idPisciculteur',
            ]);

            $newemploye = Employe::create($validated);

            return response()->json([
                'message' => 'Employé ajouté avec succès.',
                'employe' => $newemploye
            ]);
        } catch (\Exception $e) {
            // Log the exception message
            Log::error($e->getMessage());
            // Return a generic error response
            return response()->json(['error' => 'Pisciculteur introuvable.'], 500);
        }
    }

    // Supprimer un employé
    public function delete_employe($id)
    {
        $employe = Employe::find($id);
        if ($employe) {
            $employe->delete();
            $res = [
                "message" => "Supprimé avec succès",
                "status" => 200,
                "data" => $employe,
            ];
        } else {
            $res = [
                "message" => "Employé non trouvé",
                "status" => 404,
            ];
        }
        return response()->json($res);
    }

    // Modifier un employé
    public function update_employe(Request $request, $idEmploye)
    {
        try {
            // Vérifier si l'employé existe
            $employe = Employe::find($idEmploye);

            if (!$employe) {
                return response()->json([
                    'message' => 'Employé non trouvé',
                    'status' => 404,
                ], 404);
            }

            // Valider les données
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',
                'idPisciculteur' => 'required|integer|exists:pisciculteurs,idPisciculteur',
            ]);

            // Mise à jour des données de l'employé
            $employe->update($validated);

            return response()->json([
                'message' => 'Mise à jour réussie',
                'status' => 200,
                'employe' => $employe,
            ], 200);
        } catch (\Exception $e) {
            // Log the exception message
            Log::error($e->getMessage());
            // Return a generic error response
            return response()->json(['error' => 'mise à jour échoué! pisciculteur n/existe pas.'], 500);
        }
    }

}

