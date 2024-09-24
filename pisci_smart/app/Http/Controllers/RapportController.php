<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vente;
use App\Models\Depense;
use App\Models\Perte;
use App\Models\Cycle;

class RapportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Générer le rapport par cycle
    public function generateReport($idCycle)
    {
        // Vérifier si le cycle existe
        $cycle = Cycle::find($idCycle);
        if (!$cycle) {
            return response()->json(['message' => 'Cycle non trouvé'], 404);
        }

        // Obtenir les ventes, dépenses, pertes liées au cycle
        $ventes = Vente::where('idCycle', $idCycle)->get();
        $depenses = Depense::where('idCycle', $idCycle)->get();
        $pertes = Perte::where('idCycle', $idCycle)->get();

        // Calcul du total des ventes en quantité
        $totalVentesQuantite = $ventes->sum('quantite');

        // Calcul du total des montants de ventes, dépenses et pertes
        $totalVentesMontant = $ventes->sum('montant'); // Montant total des ventes
        $totalDepensesMontant = $depenses->sum('montant'); // Montant total des dépenses
        $totalPertesNombre = $pertes->count(); // Nombre total de pertes

        // Calcul du bénéfice
        $benefice = $totalVentesMontant - $totalDepensesMontant;


             // Vérifier si la date actuelle est après la date de fin du cycle
             $dateActuelle = now(); // Obtenir la date actuelle
             $cycleTermineParDate = $dateActuelle->greaterThanOrEqualTo($cycle->DateFin);

        // Vérification si tous les poissons ont été vendus (cycle terminé)
        $cycleTermine = $totalVentesQuantite >= $cycle->NbrePoisson || $cycleTermineParDate;;

        // Préparer les détails des ventes
        $ventesDetails = $ventes->map(function ($vente) {
            return [
                'nom' => $vente->nom,
                'date' => $vente->date,
                'montant' => $vente->montant,
                'quantite' => $vente->quantite,
            ];
        });

        // Préparer les détails des dépenses
        $depensesDetails = $depenses->map(function ($depense) {
            return [
                'nom' => $depense->nom,
                'date' => $depense->date,
                'montant' => $depense->montant,
            ];
        });

        // Retourner le rapport sous forme de réponse JSON
        return response()->json([
            'cycle' => $cycle->idCycle,
            'nombre_ventes' => $ventes->count(),
            'quantite_totale_ventes' => $totalVentesQuantite,
            'montant_total_ventes' => $totalVentesMontant,
            'ventes_details' => $ventesDetails,

            'nombre_depenses' => $depenses->count(),
            'montant_total_depenses' => $totalDepensesMontant,
            'depenses_details' => $depensesDetails,

            'nombre_pertes' => $totalPertesNombre,
            'benefice' => $benefice,

            // Statut du cycle (en fonction des ventes)
            'cycle_statut' => $cycleTermine ? 'Terminé' : 'En cours',
        ], 200);
    }


    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
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
