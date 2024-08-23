<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Dispositif;

class NotificationController extends Controller
{
    // Afficher tous les notifications
    public function get_all_notification()
    {
        $notification = Notification::all();
        return response()->json($notification);
    }

        // Afficher une notification
        public function getNotificationById($id)
        {
            try {
                $notification = Notification::find($id);

                if (! $notification) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'notification non trouvé'
                    ], 404);
                }

                return response()->json([
                    'status' => 'success',
                    'data' => $notification
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Une erreur s\'est produite lors de la récupération de la notification.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

    //supprimer une nourriture
public function delete_notification($id)
{
    $notification = Notification::find($id);
    if ($notification) {
        $notification->delete();
        $res = [
            "message" => "Supprimé avec succès",
            "status" => 200,
            "data" => $notification,
        ];
    } else {
        $res = [
            "message" => "notification non trouvé",
            "status" => 404,
        ];
    }

    return response()->json($res);
}

     // Créer une notification
     public function create_notification(Request $request)
     {
         try {
             $validated = $request->validate([
                 'message' => 'required|string|max:255',
                 'idDispo' => 'required|integer|exists:dispositifs,idDispo',
             ]);

             $newnotification = Notification::create($validated);

             return response()->json([
                 'message' => 'Notification ajouté avec succès.',
                 'notification' =>  $newnotification
             ]);
         } catch (\Exception $e) {
            // Log the exact exception message for debugging purposes
            Log::error('Erreur lors de la création de la notification : ' . $e->getMessage());

            // Return the actual error message in the response (temporarily, for debugging)
            return response()->json(['error' => 'Une erreur est survenue: ' . $e->getMessage()], 500);
        }

         }



           // Modifier une notification
    public function update_notification(Request $request, $idNotif)
    {
        try {
            // Vérifier si la notif existe
            $notification = Notification::find($idNotif);

            if (!$notification) {
                return response()->json([
                    'message' => 'Notification non trouvé',
                    'status' => 404,
                ], 404);
            }

            // Valider les données
            $validated = $request->validate([
                'message' => 'required|string|max:255',
                'idDispo' => 'required|integer|exists:dispositifs,idDispo',
            ]);

            // Mise à jour des données de la notification
            $notification->update($validated);

            return response()->json([
                'message' => 'Mise à jour réussie',
                'status' => 200,
                'notification' => $notification,
            ], 200);
        } catch (\Exception $e) {
            // Log the exception message
            Log::error($e->getMessage());
            // Return a generic error response
            return response()->json(['error' => 'mise à jour échoué! dispositif n/existe pas.'], 500);
        }
      }
     }


