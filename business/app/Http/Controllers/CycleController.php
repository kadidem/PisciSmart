<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCycleRequest;
use App\Http\Resources\CycleResource;
use App\Models\Cycle;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        return response()->json(Cycle::all());
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
    public function store(request $request)
    {
        $validated = $request->validate([
            'NumCycle' => 'required|',
            'AgePoisson' => 'required|',
            'NbrePoisson' => 'required|',
            'Espece' => 'required|max:255',
            'DateDebut' => 'required|',
            'DateFin' => 'required|',
        ]);
        $cycle = Cycle::create($validated);
        return new CycleResource($cycle);
    }

    /**
     * Display the specified resource.
     */
    public function show(request $request, Cycle $cycle)
    {
        return new CycleResource($cycle);
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
    public function update(UpdateCycleRequest $request, Cycle $cycle)
    {

        $validated = $request->validated();
        $cycle->update($validated);
        return new CycleResource($cycle);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(request $request, Cycle $cycle)
    {
        $cycle->delete();
        return response()->noContent();
    }
}
