<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class MessageController extends Controller
{
    public function store(Request $request)
    {
        // Afficher les données reçues pour le débogage
        Log::info('Données reçues:', $request->all());

        // Valider les données
        $validatedData = $request->validate([
            'expediteur_pisciculteur_id' => 'nullable|exists:pisciculteurs,idPisciculteur',
            'expediteur_visiteur_id' => 'nullable|exists:visiteurs,idVisiteur',
            'destinataire_pisciculteur_id' => 'nullable|exists:pisciculteurs,idPisciculteur',
            'destinataire_visiteur_id' => 'nullable|exists:visiteurs,idVisiteur',
            'contenu' => 'required|string',
            'lu' => 'boolean'
        ]);

        // Vérifier qu'un seul type d'expéditeur et de destinataire est renseigné
        $expediteurPisciculteur = $validatedData['expediteur_pisciculteur_id'] ?? null;
        $expediteurVisiteur = $validatedData['expediteur_visiteur_id'] ?? null;
        $destinatairePisciculteur = $validatedData['destinataire_pisciculteur_id'] ?? null;
        $destinataireVisiteur = $validatedData['destinataire_visiteur_id'] ?? null;

        if (($expediteurPisciculteur && $expediteurVisiteur) ||
            ($destinatairePisciculteur && $destinataireVisiteur)) {
            return response()->json(['message' => 'Un seul type d\'expéditeur et de destinataire doit être renseigné.'], 400);
        }

        // Créer le message
        $message = Message::create($validatedData);

        return response()->json(['message' => 'Message créé avec succès.', 'data' => $message], 201);
    }




    //pour formatter le temps d'envoi(1h ago, 2days ago)
    public function getFormattedMessages()
    {
        $messages = Message::all();

        foreach ($messages as $message) {
            $message->formatted_time = Carbon::parse($message->created_at)->diffForHumans();
        }

        return response()->json(['messages' => $messages], 200);
    }



     //récuperer tous les messages d'un destinateur
     public function getMessagesByDestinataire(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'pisciculteur_id' => 'nullable|exists:pisciculteurs,idPisciculteur',
             'visiteur_id' => 'nullable|exists:visiteurs,idVisiteur',
         ]);

         if ($validator->fails()) {
             return response()->json(['message' => 'Les paramètres fournis sont invalides.'], 400);
         }

         $pisciculteurId = $request->query('pisciculteur_id');
         $visiteurId = $request->query('visiteur_id');

         if ($pisciculteurId) {
             $messages = Message::where('destinataire_pisciculteur_id', $pisciculteurId)->get();
         } elseif ($visiteurId) {
             $messages = Message::where('destinataire_visiteur_id', $visiteurId)->get();
         } else {
             return response()->json(['message' => 'Vous devez fournir un ID de destinataire.'], 400);
         }

         if ($messages->isEmpty()) {
             return response()->json(['message' => 'Aucun message trouvé pour ce destinataire.'], 404);
         }

         // Retourner les messages avec le temps formaté
         return response()->json(['messages' => $messages], 200);
     }



     //Récuperer tous les messages d'un expéditeur
     public function getMessagesByExpediteur(Request $request)
     {
         $pisciculteurId = $request->query('pisciculteur_id');
         $visiteurId = $request->query('visiteur_id');

         // Vérifier quel ID est présent et filtrer les messages en conséquence
         if ($pisciculteurId) {
             // Filtrer les messages pour les pisciculteurs
             $messages = Message::where('expediteur_pisciculteur_id', $pisciculteurId)->get();
         } elseif ($visiteurId) {
             // Filtrer les messages pour les visiteurs
             $messages = Message::where('expediteur_visiteur_id', $visiteurId)->get();
         } else {
             // Si aucun paramètre n'est fourni, retourner une réponse d'erreur
             return response()->json(['message' => 'Vous devez fournir un ID de expediteur.'], 400);
         }

         // Vérifier si des messages ont été trouvés
         if ($messages->isEmpty()) {
             return response()->json(['message' => 'Aucun message trouvé pour ce expediteur.'], 404);
         }

         // Retourner les messages filtrés
         return response()->json(['messages' => $messages], 200);
     }

     //nbre de message total par destinataire
     public function countMessagesByDestinataire($id)
     {
         // Compter le nombre de messages pour le destinataire donné
         $count = Message::where('destinataire_pisciculteur_id', $id)
                         ->orWhere('destinataire_visiteur_id', $id)
                         ->count();

         // Vérifier si des messages existent pour ce destinataire
         if ($count === 0) {
             return response()->json(['message' => 'Aucun message trouvé pour ce destinataire.'], 404);
         }
         return response()->json(['total_messages' => $count], 200);
     }


     //supprimer un msg spécifique
     public function deleteMessage($id)
     {
         $message = Message::find($id);

         if (!$message) {
             return response()->json(['message' => 'Message non trouvé.'], 404);
         }

         $message->delete();
         return response()->json(['message' => 'Message supprimé avec succès.'], 200);
     }

     //pour marquer un msg comme lu
     public function markAsRead($id)
     {
         $message = Message::find($id);

         if (!$message) {
             return response()->json(['message' => 'Message non trouvé.'], 404);
         }

         $message->lu = true;
         $message->save();

         return response()->json(['message' => 'Message marqué comme lu.'], 200);
     }

    //pour obtenir les msg non lus
    public function getUnreadMessages($destinataireId)
    {
        // Vérifie si le destinataire est un pisciculteur ou un visiteur
        $messages = Message::where(function ($query) use ($destinataireId) {
            $query->where('destinataire_pisciculteur_id', $destinataireId)
                  ->orWhere('destinataire_visiteur_id', $destinataireId);
        })
        ->where('lu', false)
        ->get();

        return response()->json(['messages' => $messages], 200);
    }









}



