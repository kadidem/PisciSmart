<?php

namespace App\Http\Controllers;

use App\Models\Visiteur;
use Illuminate\Http\Request;

class VisiteurController extends Controller
{
    //afficher tous les visiteurs
    public function get_all_visiteur(){
        $visiteur=Visiteur::all();
        return response()->json( $visiteur);
    }

        //creer un nouveau visiteur
        public function create_visiteur(Request $request)
        {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'required|string|max:255',
                'adresse' => 'required|string|max:255',

            ]);

            $newvisiteur = Visiteur::create($validated);
            return response()->json(  $newvisiteur);
        }

         //supprimer un visiteur
    public function delete_visiteur($id)
    {
        $visiteur = Visiteur::find($id);
        if ($visiteur) {
            $visiteur->delete();
            $res = [
                "message" => "Supprimé avec succès",
                "status" => 200,
                "data" => $visiteur,
            ];
        } else {
            $res = [
                "message" => "visiteur non trouvée",
                "status" => 404,
            ];
        }
        return response()->json($res);
    }

    //modifier visiteur
    public function update_visiteur(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
        ]);
        $visiteur= Visiteur::find($id);

        if ($visiteur) {
            $visiteur->update($validated);

            $res = [
                "message" => "Mise à jour réussie",
                "status" => 200,
                "data" => $visiteur,
            ];
        } else {
            $res = [
                "message" => "Visiteur non trouvée",
                "status" => 404,
            ];
        }

        return response()->json($res);
    }
}
