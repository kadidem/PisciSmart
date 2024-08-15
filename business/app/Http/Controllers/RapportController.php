<?php

namespace App\Http\Controllers;

use App\Http\Resources\RapportResource;
use App\Models\Cycle;
use App\Models\Rapport;

use Illuminate\Http\Request;

class RapportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function generate($id)
{
    // Trouve le cycle par ID
    $cycle = Cycle::findOrFail($id);

    // Calcul des totaux pour le cycle spécifié
    $totalVentes = $cycle->ventes->sum('montant');
    $totalDepenses = $cycle->depenses->sum('montant');
    $totalPertes = $cycle->pertes->sum('NbreMort');

    // Calcul du bénéfice
    $benefice = $totalVentes - $totalDepenses;

    // Retourne les données en format JSON
    return response()->json([
        'totalVentes' => $totalVentes,
        'totalDepenses' => $totalDepenses,
        'totalPertes' => $totalPertes,
        'benefice' => $benefice,
    ], 200);
}
    public function index()
    {
        return response()->json(Rapport::all());
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(request $request, Rapport $rapport)
    {
        return new RapportResource($rapport);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
