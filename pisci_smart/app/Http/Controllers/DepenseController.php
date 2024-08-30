<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;


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
            'montant' => 'required|numeric',
            'date' => 'required|date|before_or_equal:today',
            'idCycle' => 'required|exists:cycles,idCycle'

        ],
        [
            'date.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.',
        ]
    );


        // Créer un cycle
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
            'date.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.',
        ]
    );


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
