<?php

namespace App\Http\Controllers;

use App\Models\Bassin;
use App\Models\Cycle;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


use App\Models\PisciculteurNotification;

class CycleController extends Controller
{
    public function index(Request $request)
    {
        // Obtenir tous les cycles
        $cycles = Cycle::all();

        // Préparer les détails des cycles avec leur statut
        $cyclesDetails = $cycles->map(function ($cycle) {

            // Obtenir les ventes liées à chaque cycle
            $ventes = Vente::where('idCycle', $cycle->idCycle)->get();

            // Calcul du total des poissons vendus
            $totalVentesQuantite = $ventes->sum('quantite');

            // Vérifier si la date actuelle est après la date de fin du cycle
            $dateActuelle = now(); // Obtenir la date actuelle
            $cycleTermineParDate = $dateActuelle->greaterThanOrEqualTo($cycle->DateFin);

            // Déterminer si le cycle est terminé
            $cycleTermine = $totalVentesQuantite >= $cycle->NbrePoisson || $cycleTermineParDate;;

            return [
                'idCycle' => $cycle->idCycle,
                'AgePoisson' => $cycle->AgePoisson,
                'NbrePoisson' => $cycle->NbrePoisson,
                'DateDebut' => $cycle->DateDebut,
                'DateFin' => $cycle->DateFin,
                'NumCycle' => $cycle->NumCycle,
                'espece' => $cycle->espece,
                'statut_Cycle' => $cycleTermine ? 'Terminé' : 'En cours',
            ];
        });

        // Retourner la liste des cycles avec leur statut
        return response()->json($cyclesDetails);
    }

    public function getCyclesByBassin($idBassin)
    {
        // Vérifier si le bassin existe
        $bassin = Bassin::find($idBassin);
        if (!$bassin) {
            return response()->json(['message' => 'Bassin non trouvé'], 404);
        }
    
        // Récupérer les cycles associés au bassin
        $cycles = Cycle::where('idBassin', $idBassin)->get();
    
        // Retourner les cycles sous forme de JSON
        return response()->json($cycles);
    }



    public function store(Request $request)
    {

        // // Récupérer l'utilisateur connecté (Pisciculteur)
        // $user = Auth::guard('sanctum')->user();

        // // Vérifier si le compte est désactivé
        // if ($user->status == 0) {
        //     return response()->json(['message' => 'Votre compte est désactivé. Vous ne pouvez pas ajouter de cycle.'], 403);
        // }


        $validatedData = $request->validate(
            [
                'AgePoisson' => 'required|integer',
                'NbrePoisson' => 'required|integer',
                'DateDebut' => 'required|date|before_or_equal:today',
                'NumCycle' => 'required|integer|unique:cycles,NumCycle',
                'espece' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'idBassin' => 'required|exists:bassins,idBassin'
            ],
            [
                'DateDebut.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.',
            ]
        );

        $bassin = Bassin::find($request->idBassin);

        // Vérifier si le bassin a été trouvé
        if (!$bassin) {
            return response()->json(['message' => 'Bassin non trouvé'], 404);
        }


        // Vérifier s'il existe déjà un cycle en cours dans ce bassin
        $cycleEnCours = Cycle::where('idBassin', $request->idBassin)
            ->where('DateFin', '>', now()) // Si la date de fin est supérieure à la date actuelle, le cycle est toujours en cours
            ->first();

        if ($cycleEnCours) {
            return response()->json([
                'error' => 'Un cycle est déjà en cours dans ce bassin. Vous devez terminer ce cycle avant d\'en créer un nouveau.'
            ], 422);
        }



        // Calculer automatiquement la date de fin (ajouter 6 mois à la date de début)
        $validatedData['DateFin'] = Carbon::parse($validatedData['DateDebut'])->addMonths(6);



        // Déterminer les normes en fonction de la dimension et de l'unité du bassin
        $dimension = $bassin->dimension;
        $unite = $bassin->unite;
        // Logique de validation des poissons en fonction de la dimension du bassin
        if ($unite == 'm3') {
            // Validation pour les bassins en m3
            $maxPoisson = $dimension * 100; // Exemple : 1m3 = 100 poissons
        } elseif ($unite == 'm2') {
            // Validation pour les bassins en m2
            $maxPoisson = $dimension * 45; // Exemple : 1m2 = 45 poissons
        } else {
            return response()->json(['message' => "Unité non reconnue"], 400);
        }
        $maxPoissons = $this->getMaxPoissonsForBassin($dimension, $unite);

        // Vérifier que le nombre de poissons respecte les normes
        if ($validatedData['NbrePoisson'] > $maxPoissons) {
            return response()->json([
                'error' => "Le nombre de poissons dépasse la limite autorisée pour un bassin de {$dimension}{$unite}. Maximum autorisé: {$maxPoissons} poissons."
            ], 422);
        }

        // Créer le cycle avec les données validées
        $cycle = Cycle::create($validatedData);

        // Retourner la réponse avec les détails du cycle créé
        return response()->json([
            'message' => 'Cycle créé avec succès',
            'cycle' => $cycle
        ], 201);
    }

    // les dimensions par poisson Bassin et cycle
    private function getMaxPoissonsForBassin($dimension, $unite)
    {
        $normesM3 = [
            1 => 100,
            2 => 200,
            3 => 300,
            4 => 400,
            5 => 500,
            6 => 600,
            8 => 800,
            10 => 1000,
            15 => 1500,
            20 => 2000,
            25 => 2500,
            30 => 3000
        ];

        $normesM2 = [
            1 => 45,
            2 => 90,
            3 => 135,
            4 => 180,
            5 => 225,
            6 => 270,
            8 => 315,
            10 => 360,
            15 => 405,
            20 => 450,
            25 => 500,
            30 => 540
        ];

        // Si l'unité est en mètres cubes
        if ($unite === 'm3') {
            return $normesM3[$dimension] ?? 0;
        }

        // Si l'unité est en mètres carrés
        if ($unite === 'm2') {
            return $normesM2[$dimension] ?? 0;
        }

        return 0; // Valeur par défaut si l'unité n'est ni m2 ni m3
    }

       //calcul bénéfice (j'ai ajouté)
       public function getTotaux()
       {
           // Calcul du total des dépenses
           $totalDepenses = DB::table('depenses')->sum('montant');

           // Calcul du total des ventes
           $totalVentes = DB::table('ventes')->sum('montant');

           // Calcul du bénéfice
           $benefice = $totalVentes - $totalDepenses;

           // Retourner les résultats au format JSON
           return response()->json([
               'totalDepenses' => $totalDepenses,
               'totalVentes' => $totalVentes,
               'benefice' => $benefice
           ]);
       }




    public function show($id)
    {
        // Récupérer le cycle avec l'ID fourni
        $cycle = Cycle::findOrFail($id);

        // Obtenir les ventes associées au cycle
        $ventes = Vente::where('idCycle', $cycle->idCycle)->get();

        // Calcul du total des poissons vendus
        $totalVentesQuantite = $ventes->sum('quantite');

        // Vérifier si la date actuelle est après la date de fin du cycle
        $dateActuelle = now(); // Obtenir la date actuelle
        $cycleTermineParDate = $dateActuelle->greaterThanOrEqualTo($cycle->DateFin);

        // Déterminer si le cycle est terminé
        $cycleTermine = $totalVentesQuantite >= $cycle->NbrePoisson  || $cycleTermineParDate;;

        // Retourner les informations du cycle avec le statut
        return response()->json([
            'idCycle' => $cycle->idCycle,
            'AgePoisson' => $cycle->AgePoisson,
            'NbrePoisson' => $cycle->NbrePoisson,
            'DateDebut' => $cycle->DateDebut,
            'DateFin' => $cycle->DateFin,
            'NumCycle' => $cycle->NumCycle,
            'espece' => $cycle->espece,
            '   ' => $cycleTermine ? 'Terminé' : 'En cours',
        ]);
    }



    public function update(Request $request, $id)
    {
        $validatedData =  $request->validate([
            'AgePoisson' => 'required|integer',
            'NbrePoisson' => 'required|integer',
            'DateDebut' => 'required|date',
            'espece' => 'required|string|max:255',
            //'idBassin' => 'required|exists:bassins,idBassin'
        ]);
        $cycle = Cycle::findOrFail($id);

        // Vérifier si le cycle a déjà commencé (DateDebut <= aujourd'hui)
        if ($cycle->DateDebut <= now()) { {
                // Retourner un message personnalisé si l'utilisateur essaie de modifier l'âge du poisson ou la date de début
                return response()->json([
                    'message' => 'Cycle en cours, on ne peut pas modifier l\'âge des poissons ou la date de début.',
                ], 400);
            }
            // Si le cycle a commencé, l'utilisateur ne peut pas modifier 'AgePoisson' ou 'DateDebut'
            $request->validate([
                'AgePoisson' => 'prohibited', // Empêcher la modification de l'âge du poisson
                'DateDebut' => 'prohibited',  // Empêcher la modification de la date de début
            ]);
        }


        // $cycle->update($request->all());
        // Mise à jour du cycle avec les champs validés
        $cycle->update($validatedData);
        return response()->json(['message' => 'Cycle mis à jour avec succès']);
    }




    public function destroy($id)
    {
        $cycle = Cycle::findOrFail($id);
        // Vérifier si le cycle est en cours (DateFin est dans le futur ou le cycle n'est pas terminé)
        if ($cycle->DateFin > now()) {
            // Retourner un message personnalisé si l'utilisateur essaie de supprimer un cycle en cours
            return response()->json([
                'message' => 'Impossible de supprimer un cycle en cours. Veuillez le terminer ou le prolonger.',
            ], 400);
        }

        // Si le cycle est terminé, on peut le supprimer

        $cycle->delete();
        return response()->json(['message' => 'Cycle supprimé avec succès']);
    }
}
