<?php

namespace App\Http\Controllers;

use App\Models\Bassin;
use App\Models\Cycle;
use App\Models\Vente;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\PisciculteurNotification;

class CycleController extends Controller
{

    public function destroyAll()
    {
        // Supprimer les ventes qui référencent les cycles
        Vente::whereNotNull('idcycle')->delete(); // Ajustez selon votre logique de référence

        // Supprimer tous les cycles
        Cycle::truncate();

        return response()->json(['message' => 'Tous les cycles et leurs ventes associées ont été supprimés avec succès.']);
    }


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


            // Calculer les poissons restants
            $poissonsRestants = $cycle->NbrePoisson - $cycle->poissons_morts - $totalVentesQuantite;

            return [
                'idCycle' => $cycle->idCycle,
                'AgePoisson' => $cycle->AgePoisson,
                'NbrePoisson' => $cycle->NbrePoisson,
                'DateDebut' => $cycle->DateDebut,
                'DateFin' => $cycle->DateFin,
                'NumCycle' => $cycle->NumCycle,
                'espece' => $cycle->espece,
                'poissons_morts' => $cycle->poissons_morts,
                'poissons_restants' => $poissonsRestants,
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
        // Validation des données d'entrée
        $validatedData = $request->validate(
            [
                'AgePoisson' => 'required|integer',
                'NbrePoisson' => 'required|integer',
                'DateDebut' => 'required|date|before_or_equal:today',
                'NumCycle' => 'required|integer|unique:cycles,NumCycle',
                'espece' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'idBassin' => 'required|exists:bassins,idBassin',
                'numero_serie' => 'nullable|string|max:255' // Validation pour numero_serie
            ],
            [
                'DateDebut.before_or_equal' => 'La date ne peut pas être dans le futur. Veuillez entrer une date valide.'
            ]
        );

        // Trouver le bassin associé
        $bassin = Bassin::find($request->idBassin);

        // Vérifier si le bassin existe
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

        // Logique pour déterminer le nombre maximal de poissons permis dans le bassin
        $dimension = $bassin->dimension;
        $unite = $bassin->unite;

        if ($unite == 'm3') {
            $maxPoissons = $dimension * 100; // Exemple : 1m3 = 100 poissons
        } elseif ($unite == 'm2') {
            $maxPoissons = $dimension * 45; // Exemple : 1m2 = 45 poissons
        } else {
            return response()->json(['message' => "Unité non reconnue"], 400);
        }

        // Vérifier que le nombre de poissons respecte les normes
        if ($validatedData['NbrePoisson'] > $maxPoissons) {
            return response()->json([
                'error' => "Le nombre de poissons dépasse la limite autorisée pour un bassin de {$dimension}{$unite}. Maximum autorisé: {$maxPoissons} poissons."
            ], 422);
        }

        // Initialiser le champ poisson_vendus à 0 lors de la création du cycle
        $validatedData['poisson_vendus'] = 0;

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


    public function getTotalDepenses($id)
    {
        // Assure-toi que le modèle Depense a une relation avec Cycle
        $totalDepenses = Depense::where('idCycle', $id)->sum('montant'); // Remplace 'cycle_id' par le nom de ta colonne de référence au cycle

        return response()->json(['total_depenses' => $totalDepenses], 200);
    }


    public function getTotalVentes($id)
    {
        // Assure-toi que le modèle Depense a une relation avec Cycle
        $totalVentes = Vente::where('idCycle', $id)->sum('montant'); // Remplace 'cycle_id' par le nom de ta colonne de référence au cycle

        return response()->json(['total_ventes' => $totalVentes], 200);
    }

    public function getBenefice($id)
    {
        // Calculer le total des ventes pour ce cycle
        $totalVentes = Vente::where('idCycle', $id)->sum('montant');

        // Calculer le total des dépenses pour ce cycle
        $totalDepenses = Depense::where('idCycle', $id)->sum('montant');

        // Calculer le bénéfice en soustrayant les dépenses des ventes
        $benefice = $totalVentes - $totalDepenses;

        // Retourner uniquement le bénéfice sous forme de JSON
        return response()->json([
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

        // Mettre à jour la colonne poissons_vendus avec la somme des ventes
        $cycle->poissons_vendus = $totalVentesQuantite;

        // Calculer les poissons restants (NbrePoisson - poissons morts - poissons vendus)
        $poissonsRestants = $cycle->NbrePoisson - $cycle->poissons_morts - $totalVentesQuantite;
        $cycle->nombrePoissonsRestants = $poissonsRestants; // Utiliser le nom correct ici

        // Sauvegarder les modifications dans la base de données
        $cycle->save();

        // Vérifier si la date actuelle est après la date de fin du cycle
        $dateActuelle = now(); // Obtenir la date actuelle
        $cycleTermineParDate = $dateActuelle->greaterThanOrEqualTo($cycle->DateFin);

        // Déterminer si le cycle est terminé
        $cycleTermine = $totalVentesQuantite >= $cycle->NbrePoisson || $cycleTermineParDate;

        // Retourner les informations du cycle avec le statut
        return response()->json([
            'idCycle' => $cycle->idCycle,
            'AgePoisson' => $cycle->AgePoisson,
            'NbrePoisson' => $cycle->NbrePoisson,
            'DateDebut' => $cycle->DateDebut,
            'DateFin' => $cycle->DateFin,
            'NumCycle' => $cycle->NumCycle,
            'espece' => $cycle->espece,
            'poissons_morts' => $cycle->poissons_morts,
            'poissons_vendus' => $cycle->poissons_vendus,
            'nombrePoissonsRestants' => $cycle->nombrePoissonsRestants, // Utiliser la colonne correcte ici
            'statut_Cycle' => $cycleTermine ? 'Terminé' : 'En cours',
            'numero_serie' => $cycle->numero_serie // Suppression de la validation pour afficher la valeur
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




    //ajout de poisson mort

    public function addPoissonsMorts(Request $request, $idCycle)
    {
        // Validation des données entrantes
        $validator = Validator::make($request->all(), [
            'nombre_morts' => 'nullable|integer|min:0',
        ], [
            'nombre_morts.integer' => 'Le nombre de poissons morts doit être un entier.',
            'nombre_morts.min' => 'Le nombre de poissons morts ne peut pas être négatif.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Récupérer le cycle
        $cycle = Cycle::find($idCycle);

        if (!$cycle) {
            return response()->json(['message' => 'Cycle non trouvé'], 404);
        }

        // Récupérer le nombre de poissons morts ajouté
        $nombreMorts = $request->input('nombre_morts');

        if ($nombreMorts === null) {
            return response()->json(['message' => 'Aucun nombre de poissons morts fourni.'], 400);
        }

        // Vérifier que l'ajout de poissons morts ne dépasse pas le nombre initial
        if (($cycle->poissons_morts + $nombreMorts) > $cycle->NbrePoisson) {
            return response()->json([
                'error' => 'Le nombre total de poissons morts dépasse le nombre initial de poissons dans le cycle.',
            ], 422);
        }

        // Mettre à jour le nombre de poissons morts
        $cycle->poissons_morts += $nombreMorts;

        // Calculer le nombre de poissons restants
        $poissonsRestants = $cycle->NbrePoisson - $cycle->poissons_morts;

        // Enregistrer les modifications
        $cycle->nombrePoissonsRestants = $poissonsRestants; // Stocker le nombre restants
        $cycle->save();

        return response()->json([
            'message' => 'Nombre de poissons morts mis à jour avec succès.',
            'poissons_morts' => $cycle->poissons_morts,
            'poissons_restants' => $poissonsRestants,
        ], 200);
    }



    public function getActiveCycles()
    {
        // Récupérer les cycles dont la DateFin est supérieure ou égale à aujourd'hui
        $activeCycles = Cycle::where('DateFin', '>', now())->get();

        // Si aucun cycle n'est trouvé, retourner un message
        if ($activeCycles->isEmpty()) {
            return response()->json(['message' => 'Aucun cycle en cours trouvé'], 200);
        }

        // Préparer les détails des cycles actifs
        $activeCyclesDetails = $activeCycles->map(function ($cycle) {
            // Obtenir les ventes liées à chaque cycle
            $ventes = Vente::where('idCycle', $cycle->idCycle)->get();
            $totalVentesQuantite = $ventes->sum('quantite');

            // Calculer les poissons restants
            $poissonsRestants = $cycle->NbrePoisson - $cycle->poissons_morts - $totalVentesQuantite;

            return [
                'idCycle' => $cycle->idCycle,
                'AgePoisson' => $cycle->AgePoisson,
                'NbrePoisson' => $cycle->NbrePoisson,
                'DateDebut' => $cycle->DateDebut,
                'DateFin' => $cycle->DateFin,
                'NumCycle' => $cycle->NumCycle,
                'espece' => $cycle->espece,
                'poissons_morts' => $cycle->poissons_morts,
                'poissons_restants' => $poissonsRestants,
                'statut_Cycle' => 'En cours',
            ];
        });

        // Retourner la liste des cycles actifs avec leurs détails
        return response()->json($activeCyclesDetails);
    }
    //calcul nombre les pertes
    public function updateNombrePertes(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'nombrePertes' => 'required|integer|min:0',
        ]);

        // Trouver le cycle par son ID
        $cycle = Cycle::findOrFail($id);

        // Mettre à jour le nombre de pertes
        $cycle->nombrePertes += $request->input('nombrePertes');

        // Calculer le nombre de poissons restants
        $nombreRestant = $cycle->nombrePoissons - $cycle->nombrePertes;

        // S'assurer que le nombre de poissons restants n'est pas négatif
        if ($nombreRestant < 0) {
            return response()->json(['error' => 'Le nombre de poissons restants ne peut pas être négatif.'], 400);
        }

        // Sauvegarder les modifications
        $cycle->save();

        // Retourner une réponse JSON avec succès et les détails du cycle
        return response()->json([
            'message' => 'Le nombre de pertes a été mis à jour avec succès.',
            'nombrePoissonsRestants' => $nombreRestant,
            'cycle' => $cycle,
        ], 200);
    }

    public function destroy($id)
    {
        // Vérifier si le cycle existe
        $cycle = Cycle::find($id);

        // Si le cycle n'existe pas, retourner une erreur 404
        if (!$cycle) {
            return response()->json(['message' => 'Cycle non trouvé'], 404);
        }

        // Supprimer le cycle
        $cycle->delete();

        // Retourner une réponse confirmant la suppression
        return response()->json(['message' => 'Cycle supprimé avec succès'], 200);
    }
}
