<?php

namespace App\Http\Controllers;

use App\Models\Dispositif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DispositifController extends Controller
{
    // Afficher tous les dispositifs
    public function get_all_dispositif()
    {
        $dispositif = Dispositif::all();
        return response()->json($dispositif);
    }

    // Afficher un dispositif
    public function getDispositifById($id)
    {
        try {
            $dispositif = Dispositif::find($id);

            if (!$dispositif) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'dispositif non trouvé'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $dispositif
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur s\'est produite lors de la récupération du dispositif.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Supprimer un dispositif
    public function delete_dispositif($id)
    {
        $dispositif = Dispositif::find($id);
        if ($dispositif) {
            $dispositif->delete();
            $res = [
                "message" => "Supprimé avec succès",
                "status" => 200,
                "data" => $dispositif,
            ];
        } else {
            $res = [
                "message" => "Dispositif non trouvé",
                "status" => 404,
            ];
        }
        return response()->json($res);
    }

    // Créer un nouveau dispositif
    public function create_dispositif(Request $request)
    {
        try {
            // Valider les données de la requête
            $validated = $request->validate([
                'num' => 'required|string|unique:dispositifs|max:255', // Validation d'unicité
                'longitude' => 'required|string|max:255',
                'latitude' => 'required|string|max:255',
                'idPisciculteur' => 'required|integer|exists:pisciculteurs,idPisciculteur', // Validation de l'existence de l'idPisciculteur
            ]);

            // Créer un nouveau dispositif
            $newdispositif = Dispositif::create($validated);

            // Retourner une réponse JSON avec le dispositif créé
            return response()->json([
                'message' => 'Dispositif ajouté avec succès.',
                'dispositif' => $newdispositif
            ], 201);

        } catch (ValidationException $e) {
            // Capturer l'exception de validation et retourner les erreurs
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Gérer les autres exceptions et loguer l'erreur
            Log::error('Erreur lors de la création du dispositif: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue lors de la création du dispositif.'], 500);
        }
    }


       // Modifier un dispositif existant
    public function update_dispositif(Request $request, $id)
    {
        try {
            // Rechercher le dispositif par ID
            $dispositif = Dispositif::find($id);

            if (!$dispositif) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dispositif non trouvé'
                ], 404);
            }

            // Valider les données de la requête
            $validated = $request->validate([
                'num' => 'required|string|max:255|unique:dispositifs,num,' . $dispositif->idDispo . ',idDispo', // Utilisation de la clé primaire correcte
                'longitude' => 'required|string|max:255',
                'latitude' => 'required|string|max:255',
                'idPisciculteur' => 'required|integer|exists:pisciculteurs,idPisciculteur', // Validation de l'existence de l'idPisciculteur
            ]);

            // Mettre à jour le dispositif avec les nouvelles données validées
            $dispositif->update($validated);

            // Retourner une réponse JSON avec le dispositif mis à jour
            return response()->json([
                'message' => 'Dispositif mis à jour avec succès.',
                'dispositif' => $dispositif
            ], 200);

        } catch (ValidationException $e) {
            // Capturer l'exception de validation et retourner les erreurs
            return response()->json(['error' => 'Erreur de validation: ' . json_encode($e->errors())], 422);
        } catch (\Exception $e) {
            // Gérer les autres exceptions et loguer l'erreur
            Log::error('Erreur lors de la mise à jour du dispositif: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue: ' . $e->getMessage()], 500);
        }
    }
}


