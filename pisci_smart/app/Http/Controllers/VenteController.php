<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Models\Vente;
use App\Http\Controllers\RapportController;

use Illuminate\Http\Request;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
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
        // Validation des données de la vente
        $validatedData = $request->validate(
            [
                'idCycle' => 'required|exists:cycles,id', // Vérifier que le cycle existe
                'quantite' => 'required|integer|min:1',   // La quantité de poissons vendus doit être au moins de 1
                'prix' => 'required|numeric',             // Le prix de vente doit être un nombre
                'date_vente' => 'required|date|before_or_equal:today' // La date de vente ne peut pas être dans le futur
            ],
            [
                'date_vente.before_or_equal' => 'La date de vente ne peut pas être dans le futur.'
            ]
        );

        // Trouver le cycle correspondant
        $cycle = Cycle::find($request->idCycle);

        // Si le cycle n'existe pas, renvoyer une erreur (cette condition est en principe déjà couverte par la validation)
        if (!$cycle) {
            return response()->json(['message' => 'Cycle non trouvé'], 404);
        }

        // Vérifier si la quantité de poissons vendus ne dépasse pas le nombre total de poissons restants dans le cycle
        $poissonsRestants = $cycle->NbrePoisson - $cycle->poisson_vendus;
        if ($request->quantite > $poissonsRestants) {
            return response()->json([
                'error' => "La quantité de poissons vendus dépasse le nombre de poissons restants dans le cycle. Poissons restants : {$poissonsRestants}."
            ], 422);
        }

        // Créer la vente
        $vente = Vente::create($validatedData);

        // Mettre à jour le champ `poisson_vendus` dans le cycle en ajoutant la quantité vendue
        $cycle->poisson_vendus += $request->quantite;

        // Sauvegarder les modifications dans le cycle
        $cycle->save();

        // Retourner une réponse avec les détails de la vente créée
        return response()->json([
            'message' => 'Vente enregistrée avec succès',
            'vente' => $vente
        ], 201);
    }


    /*public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'montant' => 'required|numeric|min:1',
            'quantite' => 'required|numeric|min:1',
            'date' => 'required|date|before_or_equal:today',
            'idCycle' => 'required|exists:cycles,idCycle'
        ],
        [
            'date.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.',
        ]);

        // Obtenir le cycle lié
        $cycle = Cycle::find($request->idCycle);

        // Calculer la quantité totale de poissons vendus dans ce cycle
        $totalVendus = Vente::where('idCycle', $request->idCycle)->sum('quantite');

        // Vérifier si tous les poissons ont été vendus
        if ($totalVendus >= $cycle->NbrePoisson) {
            // Tous les poissons ont été vendus, marquer le cycle comme terminé
            $cycle->statut = 'terminé';
            $cycle->save();

            // Générer un rapport de fin de cycle (optionnel)
            $rapportController = new RapportController();
            return $rapportController->generateReport($cycle->idCycle);
        }

        // Créer une vente
        $vente = Vente::create($request->all());

        return response()->json(['message' => 'Vente créé avec succès', 'data' => $vente], 201);
    }*/

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vente = Vente::findOrFail($id);
        return response()->json($vente);
    }



    public function getVentesByCycle($idCycle)
    {
        // Vérifier si le cycle existe
        $cycle = Cycle::find($idCycle);

        if (!$cycle) {
            return response()->json(['message' => 'Cycle non trouvé'], 404);
        }

        // Récupérer toutes les ventes liées à ce cycle
        $ventes = Vente::where('idCycle', $idCycle)->get();

        // Vérifier si des ventes existent pour ce cycle
        if ($ventes->isEmpty()) {
            return response()->json(['message' => 'Aucune vente trouvée pour ce cycle'], 404);
        }

        // Retourner la liste des ventes
        return response()->json($ventes);
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
        $request->validate(
            [
                'nom' => 'required|string',
                'montant' => 'required|numeric|min:1',
                'quantite' => 'required|numeric|min:1',
                'date' => 'required|date|before_or_equal:today',
                'idCycle' => 'required|exists:cycles,idCycle'
            ],
            [
                'date.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.',
            ]
        );

        $vente = Vente::findOrFail($id);
        $vente->update($request->all());

        return response()->json(['message' => 'Vente mis à jour avec succès', 'data' => $vente], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vente = Vente::findOrFail($id);
        $vente->delete();

        return response()->json(['message' => 'Vente supprimé avec succès']);
    }
}
