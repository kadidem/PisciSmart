<?php

namespace App\Http\Controllers;

use App\Models\Pisciculteur;
use Illuminate\Http\Request;

class PisciculteurController extends Controller
{
    //afficher tous les pisciculteurs
    public function get_all_pisciculteur(){
        $pisciculteur=Pisciculteur::all();
        return response()->json( $pisciculteur);
    }

    //creer un nouveau pisciculteur
    public function create_pisciculteur(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',

        ]);

        $newpisciculteur = Pisciculteur::create($validated);
        return response()->json(  $newpisciculteur);
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
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
        ]);
        $pisciculteur= Pisciculteur::find($id);

        if ($pisciculteur) {
            $pisciculteur->update($validated);

            $res = [
                "message" => "Mise à jour réussie",
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

}
