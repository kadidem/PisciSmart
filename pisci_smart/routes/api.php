<?php

use App\Http\Controllers\EmployeController;
use App\Http\Controllers\NourritureController;
use App\Http\Controllers\PisciculteurController;
use App\Http\Controllers\VisiteurController;
use App\Http\Controllers\AdministrateurController;
use App\Http\Controllers\DispositifController;


use App\Models\Pisciculteur;
use App\Models\Nourriture;
use App\Models\Empploye;
use App\Models\Visiteur;
use App\Models\Administrateur;
use App\Models\Dispositif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//nourriture
Route::get("/nourriture",[NourritureController::class,'get_all_nourriture']);
Route::get('/nourriture/{id}', [NourritureController::class, 'getNourritureById']);
Route::post("/nourriture",[NourritureController::class,'create_nourriture']);
Route::delete("/nourriture/{id}",[NourritureController::class,'delete_nourriture']);
Route::put('/nourriture/{id}', [NourritureController::class, 'update_nourriture']);


//pisciculteur
Route::get("/pisciculteur",[PisciculteurController::class,'get_all_pisciculteur']);
Route::get('/pisciculteur/{id}', [PisciculteurController::class, 'getPisciculteurById']);
Route::post("/pisciculteur",[PisciculteurController::class,'create_pisciculteur']);
Route::delete("/pisciculteur/{id}",[PisciculteurController::class,'delete_pisciculteur']);
Route::put('/pisciculteur/{id}', [PisciculteurController::class, 'update_pisciculteur']);

//employe
Route::get("/employe",[EmployeController::class,'get_all_employe']);
Route::get('/employe/{id}', [EmployeController::class, 'getEmployeById']);
Route::post("/employe",[EmployeController::class,'create_employe']);
Route::delete("/employe/{idEmploye}",[EmployeController::class,'delete_employe']);
Route::put('/employe/{idEmploye}', [EmployeController::class, 'update_employe']);

//visiteur
Route::get("/visiteur",[VisiteurController::class,'get_all_visiteur']);
Route::get('/visiteur/{id}', [VisiteurController::class, 'getVisiteurById']);
Route::post("/visiteur",[VisiteurController::class,'create_visiteur']);
Route::delete("/visiteur/{idVisiteur}",[VisiteurController::class,'delete_visiteur']);
Route::put('/visiteur/{idVisiteur}', [VisiteurController::class, 'update_visiteur']);

//bassin


//administrateur
Route::get('/administrateur', [AdministrateurController::class, 'get_all_admin']);
Route::get('/administrateur/{id}', [AdministrateurController::class, 'getAdministrateurById']);
Route::post('/administrateur', [AdministrateurController::class, 'store']);
Route::delete('/administrateur/{idAdmi}', [AdministrateurController::class, 'destroy']);
Route::put('/administrateur/{idAdmi}', [AdministrateurController::class, 'update']);

//dispositif


