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
use App\Http\Controllers\PisciculteurController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\NourritureController;
use App\Http\Controllers\VisiteurController;
use App\Http\Controllers\DispositifController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BassinController;
use App\Models\Dispositif;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});




//picsciculteur
Route::get('/pisciculteur', [PisciculteurController::class, 'get_all_pisciculteur']);
Route::get('/pisciculteur/{id}', [PisciculteurController::class, 'getPisciculteurById']);
Route::post('/pisciculteur', [PisciculteurController::class, 'create_pisciculteur']);
Route::put('/pisciculteur/{id}', [PisciculteurController::class, 'update_pisciculteur']);
Route::delete('/pisciculteur/{id}', [PisciculteurController::class, 'delete_pisciculteur']);

//employé
Route::get('/employe', [EmployeController::class, 'get_all_employe']);
Route::get('/employe/{id}', [EmployeController::class, 'getEmployeById']);
Route::post('/employe', [EmployeController::class, 'create_employe']);
Route::put('/employe/{id}', [EmployeController::class, 'update_employe']);
Route::delete('/employe/{id}', [EmployeController::class, 'delete_employe']);

//nourriture
Route::get('/nourriture', [NourritureController::class, 'get_all_nourriture']);
Route::get('/nourriture/{id}', [NourritureController::class, 'getNourritureById']);
Route::post('/nourriture', [NourritureController::class, 'create_nourriture']);
Route::put('/nourriture/{id}', [NourritureController::class, 'update_nourriture']);
Route::delete('/nourriture/{id}', [NourritureController::class, 'delete_nourriture']);


//visiteur
Route::get('/visiteur', [VisiteurController::class, 'get_all_visiteur']);
Route::get('/visiteur/{id}', [VisiteurController::class, 'getVisiteurById']);
Route::post('/visiteur', [VisiteurController::class, 'create_visiteur']);
Route::put('/visiteur/{id}', [VisiteurController::class, 'update_visiteur']);
Route::delete('/visiteur/{id}', [VisiteurController::class, 'delete_visiteur']);


//dispositif
Route::get('/dispositif', [DispositifController::class, 'get_all_dispositif']);
Route::get('/dispositif/{id}', [DispositifController::class, 'getDispositifById']);
Route::post('/dispositif', [DispositifController::class, 'create_dispositif']);
Route::put('/dispositif/{id}', [DispositifController::class, 'update_dispositif']);
Route::delete('/dispositif/{id}', [DispositifController::class, 'delete_dispositif']);


//notification
Route::get('/notification', [NotificationController::class, 'get_all_notification']);
Route::get('/notification/{id}', [NotificationController::class, 'getNotificationById']);
Route::post('/notification', [NotificationController::class, 'create_notification']);
Route::put('/notification/{id}', [NotificationController::class, 'update_notification']);
Route::delete('/notification/{id}', [NotificationController::class, 'delete_notification']);

//bassin
Route::get('/bassin', [BassinController::class, 'get_all_bassin']);
Route::get('/bassin/{id}', [BassinController::class, 'getBassinById']);
Route::post('/bassin', [BassinController::class, 'create_bassin']);
Route::put('/bassin/{id}', [BassinController::class, 'update_Bassin']);
Route::delete('/bassin/{id}', [BassinController::class, 'delete_Bassin']);



