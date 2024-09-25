<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;
use App\Models\Cycle;

class DepenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        return response()->json(Depense::all());
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
            'montant' => 'required|numeric|min:1',
            'date' => 'required|date|before_or_equal:today',
            'idCycle' => 'required|exists:cycles,idCycle'
        ],
        [
            'date.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.',
        ]);

        // Créer une dépense
        $depense = Depense::create($request->all());

        return response()->json(['message' => 'Depense créé avec succès', 'data' => $depense], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $depense = Depense::findOrFail($id);
        return response()->json($depense);
    }


    public function getDepensesByCycle($idCycle)
    {
        // Vérifier si le cycle existe
        $cycle = Cycle::find($idCycle);

        if (!$cycle) {
            return response()->json(['message' => 'Cycle non trouvé'], 404);
        }

        // Récupérer toutes les dépenses associées à ce cycle
        $depenses = Depense::where('idCycle', $idCycle)->get();

        // Vérifier si des dépenses existent pour ce cycle
        if ($depenses->isEmpty()) {
            return response()->json(['message' => 'Aucune dépense trouvée pour ce cycle'], 404);
        }

        // Retourner la liste des dépenses
        return response()->json($depenses);
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
            'montant' => 'required|numeric|min:1',
            'date' => 'required|date|before_or_equal:today',
            'idCycle' => 'required|exists:cycles,idCycle'
        ],
        [
            'date.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.',
        ]);

        $depense = Depense::findOrFail($id);
        $depense->update($request->all());

        return response()->json(['message' => 'Depense mis à jour avec succès']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $depense = Depense::findOrFail($id);
        $depense->delete();

        return response()->json(['message' => 'Depense supprimé avec succès']);
    }

    
}

