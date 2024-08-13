<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Http\Request;

class EmployeController extends Controller
{
   //Afficher toutes les employes
   public function get_all_employe(){
    $employe=Employe::all();
    return response()->json($employe);
  }

    //Créer un nouveau employe
  public function create_employe(Request $request)
  {
      $validated = $request->validate([
          'idPisciculteur' => 'required|integer|exists:pisciculteurs,id',
          'nom' => 'required|string|max:255',
          'prenom' => 'required|string|max:255',
          'telephone' => 'required|string|max:255',
          'adresse' => 'required|string|max:255',
          'idPisciculteur' => 'required|string|max:255',

      ]);

      $newemploye = Employe::create($validated);
      return response()->json(  $newemploye);
  }

    //supprimer un employe
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
                "message" => "Employe non trouvée",
                "status" => 404,
            ];
        }
        return response()->json($res);
    }
     //modifier employe
     public function update_employe(Request $request, $idEmploye)
     {
         $validated = $request->validate([
             'nom' => 'required|string|max:255',
             'prenom' => 'required|string|max:255',
             'telephone' => 'required|string|max:255',
             'adresse' => 'required|string|max:255',

         ]);
         $employe= Employe::find($idEmploye);

         if ($employe) {
             $employe->update($validated);

             $res = [
                 "message" => "Mise à jour réussie",
                 "status" => 200,
                 "data" => $employe,
             ];
         } else {
             $res = [
                 "message" => "Employe non trouvée",
                 "status" => 404,
             ];
         }

         return response()->json($res);
     }


}
