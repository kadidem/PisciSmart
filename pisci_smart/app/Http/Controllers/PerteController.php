<?php

namespace App\Http\Controllers;

use App\Models\Perte;
use Illuminate\Http\Request;

class PerteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        return response()->json(Perte::all());
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
            'NbreMort' => 'required|numeric',
            'Date' => 'required|Date|before_or_equal:today',
            'idCycle' => 'required|exists:cycles,idCycle'


        ],
        [
            'Date.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.',
        ]
    );




        // Créer un cycle
        $perte = Perte::create($request->all());

        return response()->json(['message' => 'Perte créé avec succès', 'data' => $perte], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $perte = Perte::findOrFail($id);
        return response()->json($perte);
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
            'NbreMort' => 'required|numeric',
            'Date' => 'required|Date|before_or_equal:today',
            'idCycle' => 'required|exists:cycles,idCycle'

        ],
        [
            'Date.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.'
        ]
    );


        $perte = Perte::findOrFail($id);

        $perte->update($request->all());
        return response()->json(['message' => 'perte mis à jour avec succès', 'data' => $perte], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $perte = Perte::findOrFail($id);

        $perte->delete();
        return response()->json(['message' => 'Perte supprimé avec succès']);
    }
}
