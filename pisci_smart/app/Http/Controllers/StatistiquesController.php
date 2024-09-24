<?php

namespace App\Http\Controllers;

use App\Models\Pisciculteur;
use App\Models\Employe;
use App\Models\Visiteur;

class StatistiquesController extends Controller
{
    public function getStatistiquesUtilisateurs()
    {
        // Compter les pisciculteurs
        $nombrePisciculteurs = Pisciculteur::count();

        // Compter les employés associés aux pisciculteurs
        $nombreEmployes = Employe::count();

        // Compter les visiteurs
        $nombreVisiteurs = Visiteur::count();

        // Retourner les statistiques sous forme de JSON
        return response()->json([
            'nombre_pisciculteurs' => $nombrePisciculteurs,
            'nombre_employes' => $nombreEmployes,
            'nombre_visiteurs' => $nombreVisiteurs,
        ]);
    }


    public function getDetailsUtilisateurs()
    {
        // Récupérer tous les pisciculteurs avec leurs informations
        $pisciculteurs = Pisciculteur::select('idPisciculteur', 'nom', 'prenom', 'telephone', 'adresse')->get();

        // Récupérer tous les employés avec les informations de leurs pisciculteurs
        $employes = Employe::with('pisciculteur:idPisciculteur,nom,prenom')
                            ->select('idEmploye', 'nom', 'prenom', 'telephone', 'adresse', 'idPisciculteur')
                            ->get();

        // Récupérer tous les visiteurs avec leurs informations
        $visiteurs = Visiteur::select('idVisiteur', 'nom', 'prenom', 'telephone', 'adresse')->get();

        // Retourner les informations sous forme de JSON
        return response()->json([
            'pisciculteurs' => $pisciculteurs,
            'employes' => $employes,
            'visiteurs' => $visiteurs,
        ]);
    }

}
