<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Bassin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BassinController extends Controller
{
    // Afficher tous les bassins
    public function get_all_bassin()
    {
        $bassin = Bassin::all();
        return response()->json($bassin);
    }


 // Afficher un bassin
 public function getBassinById($id)
 {
     try {
         $bassin = Bassin::find($id);

         if (! $bassin) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'bassin non trouvé'
             ], 404);
         }

         return response()->json([
             'status' => 'success',
             'data' => $bassin
         ], 200);

     } catch (\Exception $e) {
         return response()->json([
             'status' => 'error',
             'message' => 'Une erreur s\'est produite lors de la récupération du bassin.',
             'error' => $e->getMessage()
         ], 500);
     }
    }

    //supprimer un bassin
public function delete_bassin($id)
{
    $bassin = Bassin::find($id);
    if ($bassin) {
        $bassin->delete();
        $res = [
            "message" => "Supprimé avec succès",
            "status" => 200,
            "data" => $bassin,
        ];
    } else {
        $res = [
            "message" => "bassin non trouvé",
            "status" => 404,
        ];
    }

    return response()->json($res);
}

// Créer un bassin
public function create_bassin(Request $request)
{
    try {
        $validated = $request->validate([
            'nomBassin' => 'required|string|max:255|unique:bassins,nomBassin',

            'dimension' => 'required|numeric|min:1', // La dimension doit être un nombre positif
            'unite' => 'required|in:m2,m3', // Valider que l'unité est soit m2, soit m3
            'description' => 'required|string|max:255',
            'idDispo' => 'required|integer|exists:dispositifs,idDispo',
        ]);

        $newbassin = Bassin::create($validated);

        return response()->json([
            'message' => 'Bassin ajouté avec succès.',
            'bassin' =>  $newbassin
        ]);
    } catch (\Exception $e) {
       // Log the exact exception message for debugging purposes
       Log::error('Erreur lors de la création du bassin : ' . $e->getMessage());

       // Return the actual error message in the response (temporarily, for debugging)
       return response()->json(['error' => 'Une erreur est survenue: ' . $e->getMessage()], 500);
   }

    }
 // Modifier bassin

    public function update_bassin(Request $request, $idBassin)
    {
        try {
            // Vérifier si le bassin existe
            $bassin = Bassin::find($idBassin);

            if (!$bassin) {
                return response()->json([
                    'message' => 'Bassin non trouvé',
                    'status' => 404,
                ], 404);
            }

            // Valider les données
            $validated = $request->validate([
                'nomBassin' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('bassins', 'nomBassin')->ignore($idBassin, 'idBassin')
                ],
                'dimension' => 'sometimes|numeric|min:1', // Valider que la dimension est un nombre positif
                'unite' => 'sometimes|in:m2,m3', // Valider que l'unité est soit m2, soit m3
                'description' => 'required|string|max:255',
                'idDispo' => 'required|integer|exists:dispositifs,idDispo',
            ]);

            // Mise à jour des données du bassin
            $bassin->update($validated);

            return response()->json([
                'message' => 'Mise à jour réussie',
                'status' => 200,
                'bassin' => $bassin,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Afficher les erreurs de validation
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Erreur lors de la mise à jour du bassin : ' . $e->getMessage());
            // Return the actual error message in the response
            return response()->json(['error' => 'Mise à jour échouée: ' . $e->getMessage()], 500);
        }
    }



}
