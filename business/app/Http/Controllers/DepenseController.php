<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDepenseRequest;
use App\Http\Resources\DepenseResource;
use App\Models\Depense;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
        $validated = $request->validate([
            'nom' => 'required|string',
            'montant' => 'required|numeric',
            'date' => 'required|date',
            'id' => 'required|exists:cycles,id',
        ]);
        $depense = Depense::create($validated);
        return new DepenseResource($depense);
    }

    /**
     * Display the specified resource.
     */
    public function show(request $request, Depense $depense)
    {
        return new DepenseResource($depense);
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
    public function update(UpdateDepenseRequest $request, Depense $depense)
    {
        $validated = $request->validated();
        $depense->update($validated);
        return new DepenseResource($depense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(request $request, Depense $depense)
    {
        $depense->delete();
        return response()->noContent();
    }
}
