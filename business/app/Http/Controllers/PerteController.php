<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePerteRequest;
use App\Http\Resources\PerteResource;
use App\Models\Perte;
use Illuminate\Http\Request;

class PerteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
        $validated = $request->validate([
            'NbreMort' => 'required|integer',
            'DateMort' => 'required|date',
            'id' => 'required|exists:cycles,id',
        ]);
        $perte = Perte::create($validated);
        return new PerteResource($perte);
    }

    /**
     * Display the specified resource.
     */
    public function show(request $request, Perte $perte)
    {
        return new PerteResource($perte);
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
    public function update(UpdatePerteRequest $request, Perte $perte)
    {
        $validated = $request->validated();
        $perte->update($validated);
        return new PerteResource($perte);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(request $request, Perte $perte)
    {
        $perte->delete();
        return response()->noContent();
    }
}
