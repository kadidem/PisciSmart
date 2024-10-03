<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\Pisciculteur;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Assure-toi que cette ligne est présente
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    // Afficher tous les employes
    public function get_all_employe()
    {
        $employe = Employe::all();
        return response()->json($employe);
    }

    //afficher un employé

    public function getEmployeById($id)
{
    try {
        // Rechercher l'employe' par ID
        $employe = Employe::find($id);

        // Si l'employe n'existe pas, retourner une erreur
        if (!$employe) {
            return response()->json([
                'status' => 'error',
                'message' => 'employe non trouvé'
            ], 404);
        }

        // Retourner l'employe trouvé
        return response()->json([
            'status' => 'success',
            'data' => $employe
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Une erreur s/est produite lors de la récupération de l/employe.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // Créer un employé
    public function create_employe(Request $request)
{
    try {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255|unique:employes',
            'adresse' => 'required|string|max:255',
            'idPisciculteur' => 'required|integer|exists:pisciculteurs,idPisciculteur',
            'password' => 'required|string|min:8',
        ]);

        // Hacher le mot de passe avant de le sauvegarder dans la base de données
        $validated['password'] = bcrypt($request->password);

        $newemploye = Employe::create($validated);

        return response()->json([
            'message' => 'Employé ajouté avec succès.',
            'employe' => $newemploye
        ]);
    } catch (\Exception $e) {
        // Log the exception message
        Log::error($e->getMessage());
        // Return a generic error response
        return response()->json(['error' => 'Erreur lors de l\'ajout de l\'employé.'], 500);
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

    //nbre total de pisciculteur par employé
    public function getTotalEmployesByPisciculteur(Request $request)
    {
        $idPisciculteur = $request->input('idPisciculteur');

        // Vérifier si le pisciculteur existe
        $pisciculteur = Pisciculteur::find($idPisciculteur);

        if (!$pisciculteur) {
            return response()->json(['message' => 'Pisciculteur non trouvé'], 404);
        }

        // Récupérer le nombre total d'employés pour ce pisciculteur
        $totalEmployes = Employe::where('idPisciculteur', $idPisciculteur)->count();

        return response()->json(['total_employes' => $totalEmployes]);
    }

    
    // Afficher uniquement le nom, prénom, téléphone, et adresse des employés d'un pisciculteur spécifique
public function getEmployeInfoByPisciculteur($idPisciculteur)
{
    try {
        // Vérifier si le pisciculteur existe
        $pisciculteur = Pisciculteur::find($idPisciculteur);

        if (!$pisciculteur) {
            return response()->json(['message' => 'Pisciculteur non trouvé'], 404);
        }

        // Utilisation de la méthode select pour récupérer uniquement les champs spécifiés
        $employes = Employe::select('nom', 'prenom', 'telephone', 'adresse')
            ->where('idPisciculteur', $idPisciculteur)
            ->get();

        // Retourner les résultats sous forme de JSON
        return response()->json([
            'status' => 'success',
            'data' => $employes
        ], 200);

    } catch (\Exception $e) {
        // Gérer les erreurs et retourner une réponse d'erreur
        return response()->json([
            'status' => 'error',
            'message' => 'Erreur lors de la récupération des informations des employés.',
            'error' => $e->getMessage()
        ], 500);
    }
}



}

