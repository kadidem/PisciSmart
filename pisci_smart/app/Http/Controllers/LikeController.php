<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LikeController extends Controller
{

      // Méthode pour obtenir tous les likes
      public function index()
      {
          try {
              $likes = Like::all(); // Récupérer tous les likes
              return response()->json($likes);
          } catch (\Exception $e) {
              return response()->json(['error' => $e->getMessage()], 500);
          }
      }

      // Méthode pour récupérer le nombre total de likes
    public function getTotalLikes()
    {
        $totalLikes = Like::count();
        return response()->json(['total_likes' => $totalLikes]);
    }

    public function toggleLike(Request $request, $postId)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'user_type' => 'required|in:pisciculteur,visiteur',
            ]);

            $userId = $request->user_id;
            $userType = $request->user_type;

            Like::toggleLike($postId, $userId, $userType);

            // Message en français
            return response()->json(['message' => 'Le like a été activé/désactivé avec succès.']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Afficher l'exception pour le débogage
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

