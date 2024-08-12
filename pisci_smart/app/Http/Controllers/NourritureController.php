<?php

namespace App\Http\Controllers;

use App\Models\Nourriture;
use Illuminate\Http\Request;

class NourritureController extends Controller
{
    public function get_all_nourriture(){
        $nourriture=Nourriture::all();
        return response()->json($nourriture);
    }
    public function create_nourriture(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'quantite' => 'required|integer',
        'date' => 'required|date',
    ]);

    $newnourriture = Nourriture::create($validated);
    return response()->json($newnourriture);
}

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
