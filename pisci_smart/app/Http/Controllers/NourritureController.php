<?php

namespace App\Http\Controllers;
use App\Models\Nourriture;
use Illuminate\Http\Request;

class NourritureController extends Controller
{   //Afficher toutes les nourritures
    public function get_all_nourriture(){
        $nourriture=Nourriture::all();
        return response()->json($nourriture);
    }



    public function getNourritureById($id)
{
    try {
        // Rechercher la nourriture par ID
        $nourriture = Nourriture::find($id);

        // Si la nourriture n'existe pas, retourner une erreur
        if (!$nourriture) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nourriture non trouvé'
            ], 404);
        }

        // Retourner la nourriture trouvé
        return response()->json([
            'status' => 'success',
            'data' => $nourriture
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Une erreur s/est produite lors de la récupération de la nourriture.',
            'error' => $e->getMessage()
        ], 500);
    }
}


    //créer une nouvelle nourriture
    public function create_nourriture(Request $request)
   {
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'quantite' => 'required|integer',
        'date' => 'required|date',
        'idCycle' => 'required|exists:cycles,idCycle'
    ]);

    $newnourriture = Nourriture::create($validated);
    return response()->json($newnourriture);
}


    //supprimer une nourriture
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

     //modifier une nourriture


public function update_nourriture(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'quantite' => 'required|integer',
            'date' => 'required|date',
        ]);
        $nourriture = Nourriture::find($id);

        if ($nourriture) {
            $nourriture->update($validated);

            $res = [
                "message" => "Mise à jour réussie",
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
}
