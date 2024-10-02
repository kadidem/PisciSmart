<?php

namespace App\Http\Controllers;

use App\Models\Pisciculteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\Dispositif;


class PisciculteurController extends Controller
{
    // Afficher tous les pisciculteurs
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
                'message' => 'Une erreur s\'est produite lors de la récupération du pisciculteur.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Créer un nouveau pisciculteur
    /*public function create_pisciculteur(Request $request)
    {
        try {
            // Valider les données de la requête
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|unique:pisciculteurs|max:20',
                'password' => 'required|string|min:6',
                'user_id' => 'required|exists:users,id',  // Valider l'ID de l'utilisateur
                'idDispo' => 'required|exists:dispositifs,idDispo', // Valider l'ID du dispositif
                'active' => 'boolean'  // Validation pour 'active'
            ]);

            // Hacher le mot de passe
            $validated['password'] = Hash::make($validated['password']);

            // Définir une valeur par défaut pour 'active' si elle n'est pas fournie
            if (!isset($validated['active'])) {
                $validated['active'] = 1;
            }

            // Créer un nouveau pisciculteur
            $newpisciculteur = Pisciculteur::create($validated);

            // Retourner une réponse JSON avec le pisciculteur créé
            return response()->json($newpisciculteur, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue'], 500);
        }
    }*/

    public function create_pisciculteur(Request $request)
    {
        try {
            // Valider les données de la requête
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|unique:pisciculteurs|max:20',
                'password' => 'required|string|min:6',
                'user_id' => 'required|exists:users,id',  // Valider l'ID de l'utilisateur
                'numero_serie' => 'required|exists:dispositifs,numero_serie', // Valider le numéro de série du dispositif
                'active' => 'boolean'  // Validation pour 'active'
            ]);

            // Hacher le mot de passe
            $validated['password'] = Hash::make($validated['password']);

            // Rechercher le dispositif avec le numéro de série fourni
            $dispositif = Dispositif::where('numero_serie', $validated['numero_serie'])->first();

            if (!$dispositif) {
                return response()->json(['error' => 'Dispositif introuvable'], 404);
            }

            // Associer l'ID du dispositif au pisciculteur
            $validated['idDispo'] = $dispositif->idDispo;

            // Définir une valeur par défaut pour 'active' si elle n'est pas fournie
            if (!isset($validated['active'])) {
                $validated['active'] = 1;
            }

            // Créer un nouveau pisciculteur
            $newpisciculteur = Pisciculteur::create($validated);

            // Retourner une réponse JSON avec le pisciculteur créé
            return response()->json($newpisciculteur, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue'], 500);
        }
    }



    // Supprimer un pisciculteur
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
                "message" => "Pisciculteur non trouvé",
                "status" => 404,
            ];
        }
        return response()->json($res);
    }

    // Modifier un pisciculteur
    public function update_pisciculteur(Request $request, $id)
    {
        try {
            // Valider les données de la requête en excluant l'ID du pisciculteur actuel pour l'unicité du téléphone
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|string|max:255|unique:pisciculteurs,telephone,' . $id . ',idPisciculteur',
                'user_id' => 'required|exists:users,id',  // Valider l'ID de l'utilisateur
                'idDispo' => 'required|exists:dispositifs,idDispo' // Valider l'ID du dispositif
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
            $res = [
                "message" => "Erreur de validation",
                "status" => 422,
                "errors" => $e->errors(),
            ];
        } catch (\Exception $e) {
            $res = [
                "message" => "Une erreur est survenue",
                "status" => 500,
            ];
        }

        return response()->json($res);
    }
}
