<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateVenteRequest;
use App\Http\Resources\VenteResource;
use App\Models\Vente;
use Illuminate\Http\Request;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
        $validated = $request->validate([
            'nom' => 'required|string',
            'montant' => 'required|numeric',
            'date' => 'required|date',
            'id' => 'required|exists:cycles,id',
        ]);
        $vente = Vente::create($validated);
        return new VenteResource($vente);
    }

    /**
     * Display the specified resource.
     */
    public function show(request $request, Vente $vente)
    {
        return new VenteResource($vente);
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
    public function update(UpdateVenteRequest $request, Vente $vente)
    {
        $validated = $request->validated();
        $vente->update($validated);
        return new VenteResource($vente);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(request $request, Vente $vente)
    {
        $vente->delete();
        return response()->noContent();
    }
}
