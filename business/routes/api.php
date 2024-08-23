<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\PerteController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\AuthController;
use App\Models\Depense;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable; // Pour les notifications
use Illuminate\Database\Eloquent\Model;  // Pour le modèle

Route::get('/user', function (Request $request) {
    return $request->user();


})->middleware('auth:sanctum');
class Pisciculteur extends Model {
    use HasApiTokens, Notifiable;
}


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::apiResource('cycles', CycleController::class);
Route::apiResource('depenses', DepenseController::class);
Route::apiResource('ventes', VenteController::class);
Route::apiResource('pertes', PerteController::class);
// ->only(['index','show', 'store','update',])
// Route::get('/cycles/{cycle}/check-age', [CycleController::class, 'checkCycleAge']);
