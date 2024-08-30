<?php
// app/Http/Controllers/TypeDemandeController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeDemande;

class TypeDemandeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $typeDemande = TypeDemande::create($request->all());

        return response()->json($typeDemande, 201);
    }

    /**
     * Afficher tous les types de demande.
     */
    public function index()
    {
        $typeDemandes = TypeDemande::all();
        return response()->json($typeDemandes);
    }

   
}



