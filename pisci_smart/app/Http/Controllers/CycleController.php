<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cycle;
use Carbon\Carbon;
use App\Http\Controllers\Notification;

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
    public function store(Request $request)
    {
        $request->validate([
            'AgePoisson' => 'required|integer',
            'NbrePoisson' => 'required|integer',
            'DateDebut' => 'required|date',
            'NumCycle' => 'required|integer|unique:cycles,NumCycle',
            'espece' => 'required|string|max:255',
        ]);

        // Créer un cycle
        $cycle = Cycle::create($request->all());

        return response()->json(['message' => 'Cycle créé avec succès', 'data' => $cycle], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cycle = Cycle::findOrFail($id);
        return response()->json($cycle);
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
        $cycle = Cycle::findOrFail($id);

        if ($cycle->DateDebut <= Carbon::now()) {
            return response()->json(['message' => 'Le cycle a déjà commencé, vous ne pouvez pas le modifier.'], 403);
        }

        $cycle->update($request->all());
        return response()->json(['message' => 'Cycle mis à jour avec succès']);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cycle = Cycle::findOrFail($id);

        if ($cycle->DateDebut <= Carbon::now()) {
            return response()->json(['message' => 'Vous ne pouvez pas supprimer un cycle en cours.'], 403);
        }

        $cycle->delete();
        return response()->json(['message' => 'Cycle supprimé avec succès']);
    }
//     public function checkCycleAge(Cycle $cycle)
// {
//     $startDate = new \DateTime($cycle->DateDebut);
//     $currentDate = new \DateTime();
//     $interval = $startDate->diff($currentDate);
//     $ageInMonths = $interval->m + ($interval->y * 12); // Calculer l'âge en mois

//     if ($ageInMonths >= 6 && $cycle->statut == 'en cours') {
//         // Envoyer la notification à l'utilisateur pour prolonger ou terminer
//         $this->notifyUserForCycle($cycle);
//     }
// }
// public function notifyUserForCycle(Cycle $cycle)
// {
//     // Logique pour envoyer une notification à l'utilisateur (email, notification, etc.)
//     $user = $cycle->pisciculteur; // Trouver l'utilisateur
//     // Notification::send($user, new CycleNotification($cycle)); // Exemple avec une notification Laravel
// }
}
