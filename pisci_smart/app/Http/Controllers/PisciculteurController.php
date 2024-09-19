<?php

namespace App\Http\Controllers;

use App\Models\Pisciculteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class PisciculteurController extends Controller
{
    //afficher tous les pisciculteurs
    public function getAllPisciculteur()
    {
        $pisciculteur = Pisciculteur::all();
        return response()->json($pisciculteur);
    }


    public function getPisciculteurById($id)
    {
        try {
            // Rechercher le pisciculteur par ID
            $pisciculteur = Pisciculteur::find($id);

            // Si le pisciculteur n'existe pas, retourner une erreur
            if (!$pisciculteur) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pisciculteur non trouvé'
                ], 404);
            }

            // Retourner le pisciculteur trouvé
            return response()->json([
                'status' => 'success',
                'data' => $pisciculteur
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur s/est produite lors de la récupération du pisciculteur.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    //creer un nouveau pisciculteur
    public function create_pisciculteur(Request $request)
    {
        try {
            // Valider les données de la requête
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|unique:pisciculteurs|max:20',
                'password' => 'required|string|min:6',
                'active' => 'boolean' // Ajouter la validation pour 'active'
            ]);

            // Ajouter le mot de passe haché
            $validated['password'] = Hash::make($validated['password']);

            // Définir une valeur par défaut pour 'active' si elle n'est pas fournie
            if (!isset($validated['active'])) {
                $validated['active'] = 1; // ou 0, selon ce que tu veux comme valeur par défaut
            }

            // Créer un nouveau pisciculteur
            $newpisciculteur = Pisciculteur::create($validated);

            // Retourner une réponse JSON avec le pisciculteur créé
            return response()->json($newpisciculteur, 201);
        } catch (ValidationException $e) {
            // Capturer l'exception de validation
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Enregistrer l'erreur dans les logs et retourner une réponse JSON
            Log::error($e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue'], 500);
        }
    }




    //supprimer un pisciculteur
    public function delete_pisciculteur($id)
    {
        $pisciculteur = Pisciculteur::find($id);
        if ($pisciculteur) {
            $pisciculteur->delete();
            $res = [
                "message" => "Supprimé avec succès",
                "status" => 200,
                "data" => $pisciculteur,
            ];
        } else {
            $res = [
                "message" => "Pisciculteur non trouvée",
                "status" => 404,
            ];
        }
        return response()->json($res);
    }

    //modifier pisciculteur
    public function update_pisciculteur(Request $request, $id)
    {
        try {
            // Valider les données de la requête en excluant l'ID du pisciculteur actuel pour l'unicité du téléphone
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|string|max:255|unique:pisciculteurs,telephone,' . $id . ',idPisciculteur',
                'adresse' => 'required|string|max:255',
            ]);

            // Trouver le pisciculteur par son ID
            $pisciculteur = Pisciculteur::find($id);

            if ($pisciculteur) {
                // Mettre à jour les données du pisciculteur
                $pisciculteur->update($validated);

                $res = [
                    "message" => "Mise à jour réussie",
                    "status" => 200,
                    "data" => $pisciculteur,
                ];
            } else {
                $res = [
                    "message" => "Pisciculteur non trouvé",
                    "status" => 404,
                ];
            }
        } catch (ValidationException $e) {
            // Capturer l'exception de validation et retourner un message d'erreur
            $res = [
                "message" => "Erreur de validation",
                "status" => 422,
                "errors" => $e->errors(),
            ];
        } catch (\Exception $e) {
            // Gérer les autres exceptions
            $res = [
                "message" => "Une erreur est survenue",
                "status" => 500,
            ];
        }

        return response()->json($res);
    }
}
