<?php

namespace App\Http\Controllers;

use App\Models\Dispositif;
use App\Models\Pisciculteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DispositifController extends Controller
{
    // Afficher tous les dispositifs
    public function get_all_dispositif()
    {
        $dispositif = Dispositif::all();
        return response()->json($dispositif);
    }

    public function generateQrCode($idDispo)
    {
        // Récupérer le dispositif à partir de son ID
        $dispositif = Dispositif::find($idDispo);
        if (!$dispositif) {
            return response()->json(['message' => 'Dispositif non trouvé'], 404);
        }

        // Chemin vers le logo de PisciSmart
        $logoPath = resource_path('images/logo.png');

        // Générer le QR code avec l'ID du dispositif
        $qrCode = QrCode::format('png')
            ->size(200)
            ->generate($dispositif->numero_serie);

        // Retourner le QR code comme une image
        return response($qrCode)->header('Content-Type', 'image/png');
    }

    public function getLocationByDispoId($idDispo)
    {
        $dispositif = Dispositif::find($idDispo);

        if ($dispositif) {
            $cityResponse = Http::get("https://nominatim.openstreetmap.org/reverse", [
                'format' => 'json',
                'lat' => $dispositif->latitude,
                'lon' => $dispositif->longitude,
            ]);

            $cityName = $cityResponse->json()['address']['city'] ?? 'Ville inconnue';

            return response()->json([
                'latitude' => $dispositif->latitude,
                'longitude' => $dispositif->longitude,
                'city' => $cityName,
                'map_url' => "https://www.openstreetmap.org/?mlat={$dispositif->latitude}&mlon={$dispositif->longitude}#map=15/{$dispositif->latitude}/{$dispositif->longitude}",
            ]);
        }

        return response()->json(['error' => 'Dispositif non trouvé'], 404);
    }

    // Afficher un dispositif
    public function getDispositifById($id)
    {
        try {
            $dispositif = Dispositif::find($id);

            if (!$dispositif) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'dispositif non trouvé'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $dispositif
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur s\'est produite lors de la récupération du dispositif.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Supprimer un dispositif
    public function delete_dispositif($id)
    {
        $dispositif = Dispositif::find($id);
        if ($dispositif) {
            $dispositif->delete();
            $res = [
                "message" => "Supprimé avec succès",
                "status" => 200,
                "data" => $dispositif,
            ];
        } else {
            $res = [
                "message" => "Dispositif non trouvé",
                "status" => 404,
            ];
        }
        return response()->json($res);
    }

    // Créer un nouveau dispositif
    public function create_dispositif(Request $request)
    {
        try {
            // Valider les données de la requête
            $validated = $request->validate([
                'num' => 'required|string|unique:dispositifs|max:255', // Validation d'unicité
                'longitude' => 'required|string|max:255',
                'latitude' => 'required|string|max:255',
            ]);

            // Générer un numéro de série unique de 4 caractères (alphanumérique)
            $numeroSerie = $this->generateNumeroSerie();

            // Ajouter le numéro de série aux données validées
            $validated['numero_serie'] = $numeroSerie;

            // Créer un nouveau dispositif
            $newdispositif = Dispositif::create($validated);

            // Retourner une réponse JSON avec le dispositif créé
            return response()->json([
                'message' => 'Dispositif ajouté avec succès.',
                'dispositif' => $newdispositif
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du dispositif: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue lors de la création du dispositif.'], 500);
        }
    }

    // Fonction pour générer un numéro de série unique de 4 caractères
    private function generateNumeroSerie()
    {
        do {
            // Générer une chaîne aléatoire de 4 caractères (alphanumérique)
            $numeroSerie = strtoupper(Str::random(4));
        } while (Dispositif::where('numero_serie', $numeroSerie)->exists()); // S'assurer que le numéro de série est unique

        return $numeroSerie;
    }

    // Modifier un dispositif existant
    public function update_dispositif(Request $request, $id)
    {
        try {
            // Rechercher le dispositif par ID
            $dispositif = Dispositif::find($id);

            if (!$dispositif) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dispositif non trouvé'
                ], 404);
            }

            // Valider les données de la requête
            $validated = $request->validate([
                'num' => 'required|string|max:255|unique:dispositifs,num,' . $dispositif->idDispo . ',idDispo',
                'longitude' => 'required|string|max:255',
                'latitude' => 'required|string|max:255',
            ]);

            // Mettre à jour le dispositif avec les nouvelles données validées
            $dispositif->update($validated);

            // Retourner une réponse JSON avec le dispositif mis à jour
            return response()->json([
                'message' => 'Dispositif mis à jour avec succès.',
                'dispositif' => $dispositif
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Erreur de validation: ' . json_encode($e->errors())], 422);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du dispositif: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue: ' . $e->getMessage()], 500);
        }
    }

    // Afficher le nombre total de dispositifs
    public function count_all_dispositifs()
    {
        try {
            // Compter le nombre total de dispositifs
            $count = Dispositif::count();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_dispositifs' => $count
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors du comptage des dispositifs: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors du comptage des dispositifs.'
            ], 500);
        }
    }

    public function ajouterDispositif(Request $request)
    {
        // Valider les données
        $request->validate([
            'numero_serie' => 'required|string',
        ]);

        $user = $request->user();

        // Vérifier si l'utilisateur est déjà un pisciculteur
        if (!$user->pisciculteur) {
            Log::info('Création d\'un nouveau pisciculteur pour l\'utilisateur ID: ' . $user->id);

            // Créer un pisciculteur pour cet utilisateur s'il n'existe pas encore
            $pisciculteur = Pisciculteur::create([
                'user_id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'telephone' => $user->telephone,
                'password' => $user->password,
                // Autres données spécifiques aux pisciculteurs
            ]);
        } else {
            Log::info('L\'utilisateur ID: ' . $user->id . ' est déjà un pisciculteur avec ID: ' . $user->pisciculteur->idPisciculteur);
            $pisciculteur = $user->pisciculteur;
        }

        // Vérifier si le dispositif avec ce numero_serie existe déjà
        Log::info('Recherche du dispositif avec le numéro de série: ' . $request->numero_serie);
        $dispositif = Dispositif::where('numero_serie', $request->numero_serie)->first();

        if ($dispositif) {
            // Si le dispositif existe, associer le pisciculteur
            Log::info('Dispositif trouvé, association du pisciculteur ID: ' . $pisciculteur->idPisciculteur . ' au dispositif ID: ' . $dispositif->id);
            $dispositif->idPisciculteur = $pisciculteur->idPisciculteur;
            $dispositif->save();

            return response()->json([
                'message' => 'Dispositif associé avec succès au pisciculteur.',
                'dispositif' => $dispositif,
                'idPisciculteur' => $pisciculteur->idPisciculteur // Ajout de l'idPisciculteur ici
            ], 200);
        } else {
            // Si le dispositif n'existe pas, retourner une erreur
            Log::warning('Le dispositif avec le numéro de série: ' . $request->numero_serie . ' n\'existe pas.');
            return response()->json([
                'message' => 'Le dispositif avec ce numéro de série n\'existe pas.'
            ], 404);
        }
    }


    //liste des dispositifs associés à un pisciculteur
    public function getDispositifsParPisciculteur(Request $request)
    {
        // Récupérer l'utilisateur authentifié
        $user = $request->user();

        // Vérifier si l'utilisateur est un pisciculteur
        if ($user->pisciculteur) {
            // Récupérer uniquement les 'num' des dispositifs associés au pisciculteur
            $dispositifs = $user->pisciculteur->dispositifs()->pluck('num');

            return response()->json([
                'dispositifs' => $dispositifs
            ], 200);
        } else {
            return response()->json([
                'message' => 'L\'utilisateur n\'est pas un pisciculteur.'
            ], 404);
        }
    }

    // Dans DispositifController
    public function getDispositifsByPisciculteur(Request $request, $idPisciculteur)
    {
        // Vérifier si le pisciculteur existe
        $pisciculteur = Pisciculteur::find($idPisciculteur);

        if (!$pisciculteur) {
            return response()->json([
                'message' => 'Pisciculteur non trouvé'
            ], 404);
        }

        // Récupérer uniquement les numéros de série des dispositifs associés au pisciculteur
        $numeroSeries = $pisciculteur->dispositifs()->pluck('numero_serie');

        // Formater les numéros de série en objets
        $formattedDispositifs = $numeroSeries->map(function ($numeroSerie) {
            return ['numero_serie' => $numeroSerie];
        });

        return response()->json($formattedDispositifs, 200);
    }
}
