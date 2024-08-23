<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use Illuminate\Http\Request;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        return response()->json(Vente::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'montant' => 'required|numeric',
            'quantite' => 'required|numeric',
            'date' => 'required|date',
            'idCycle' => 'required|exists:cycles,idCycle'

        ]);

        // Créer un cycle
        $vente = Vente::create($request->all());

        return response()->json(['message' => 'Vente créé avec succès', 'data' => $vente], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vente = Vente::findOrFail($id);
        return response()->json($vente);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string',
            'montant' => 'required|numeric',
            'date' => 'required|date|before_or_equal:today',
            'idCycle' => 'required|exists:cycles,idCycle'

        ],
        [
            'date.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.'
        ]
    );

        $vente = Vente::findOrFail($id);

        $vente->update($request->all());
        return response()->json(['message' => 'Vente mis à jour avec succès', 'data' => $vente], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vente = Vente::findOrFail($id);

        $vente->delete();
        return response()->json(['message' => 'Vente supprimé avec succès']);
    }
}
