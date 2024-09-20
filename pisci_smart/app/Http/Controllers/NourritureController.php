<?php

namespace App\Http\Controllers;

use App\Models\Nourriture;
use Illuminate\Http\Request;
use App\Models\Cycle;

class NourritureController extends Controller
{
    // Afficher toutes les nourritures
    public function get_all_nourriture()
    {
        $nourritures = Nourriture::all();
        return response()->json($nourritures);
    }
    // Récupérer les nourritures par ID de cycle// Récupérer les nourritures par ID de cycle
 


    // Obtenir une nourriture par ID
    public function getNourritureById($id)
    {
        try {
            $nourriture = Nourriture::find($id);

            if (!$nourriture) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nourriture non trouvée'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $nourriture
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur s\'est produite lors de la récupération de la nourriture.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getNourrituresByCycle($idCycle)
{
    // Vérifier si le cycle existe
    $cycle = Cycle::find($idCycle);

    if (!$cycle) {
        return response()->json(['message' => 'Cycle non trouvé'], 404);
    }

    // Récupérer toutes les nourritures associées à ce cycle
    $nourritures = Nourriture::where('idCycle', $idCycle)->get();

    // Vérifier si des nourritures existent pour ce cycle
    if ($nourritures->isEmpty()) {
        return response()->json(['message' => 'Aucune nourriture trouvée pour ce cycle'], 404);
    }

    // Retourner la liste des nourritures
    return response()->json($nourritures);
}


    // Créer une nouvelle nourriture
    public function create_nourriture(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer',
            'date' => 'required|date',
            'heure' => 'required|date_format:H:i:s',
            'idCycle' => 'required|exists:cycles,idCycle'
        ]);

        $newNourriture = Nourriture::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $newNourriture
        ], 201);
    }

    // Supprimer une nourriture
    public function delete_nourriture($id)
    {
        $nourriture = Nourriture::find($id);
        if ($nourriture) {
            $nourriture->delete();
            $res = [
                "message" => "Supprimé avec succès",
                "status" => 200,
                "data" => $nourriture,
            ];
        } else {
            $res = [
                "message" => "Nourriture non trouvée",
                "status" => 404,
            ];
        }

        return response()->json($res);
    }

    // Modifier une nourriture
    public function update_nourriture(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer',
            'date' => 'required|date',
            'heure' => 'required|date_format:H:i:s',
        ]);

        $nourriture = Nourriture::find($id);

        if ($nourriture) {
            $nourriture->update($validated);

            return response()->json([
                "message" => "Mise à jour réussie",
                "status" => 200,
                "data" => $nourriture,
            ]);
        } else {
            return response()->json([
                "message" => "Nourriture non trouvée",
                "status" => 404,
            ]);
        }
    }
}
