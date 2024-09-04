<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pisciculteur;
use App\Models\Employe;
use App\Models\Visiteur;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */

       // Désactiver un utilisateur
     // Désactiver un compte
     public function desactiverCompte($id, $type)
     {
         // Trouver l'utilisateur selon le type
         if ($type == 'pisciculteur') {
             $user = Pisciculteur::find($id);
         } elseif ($type == 'employe') {
             $user = Employe::find($id);
         } elseif ($type == 'visiteur') {
             $user = Visiteur::find($id);
         } else {
             return response()->json(['message' => 'Type d\'utilisateur invalide.'], 400);
         }

         // Vérifier si l'utilisateur existe
         if ($user) {
             $user->status = 0; // Désactiver le compte
             $user->save();
             return response()->json(['message' => 'Compte désactivé avec succès.']);
         }

         return response()->json(['message' => 'Utilisateur introuvable.'], 404);
     }

     // Activer un compte
     public function activerCompte($id, $type)
     {
         // Trouver l'utilisateur selon le type
         if ($type == 'pisciculteur') {
             $user = Pisciculteur::find($id);
         } elseif ($type == 'employe') {
             $user = Employe::find($id);
         } elseif ($type == 'visiteur') {
             $user = Visiteur::find($id);
         } else {
             return response()->json(['message' => 'Type d\'utilisateur invalide.'], 400);
         }

         // Vérifier si l'utilisateur existe
         if ($user) {
             $user->status = 1; // Activer le compte
             $user->save();
             return response()->json(['message' => 'Compte activé avec succès.']);
         }

         return response()->json(['message' => 'Utilisateur introuvable.'], 404);
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
