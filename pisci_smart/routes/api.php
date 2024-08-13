<?php

use App\Http\Controllers\EmployeController;
use App\Http\Controllers\NourritureController;
use App\Http\Controllers\PisciculteurController;
use App\Http\Controllers\VisiteurController;
use App\Models\Pisciculteur;
use App\Models\Nourriture;
use App\Models\Empploye;
use App\Models\Visiteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/nourriture",[NourritureController::class,'get_all_nourriture']);
Route::post("/nourriture",[NourritureController::class,'create_nourriture']);
Route::delete("/nourriture/{id}",[NourritureController::class,'delete_nourriture']);
Route::put('/nourriture/{id}', [NourritureController::class, 'update_nourriture']);


Route::get("/pisciculteur",[PisciculteurController::class,'get_all_pisciculteur']);
Route::post("/pisciculteur",[PisciculteurController::class,'create_pisciculteur']);
Route::delete("/pisciculteur/{id}",[PisciculteurController::class,'delete_pisciculteur']);
Route::put('/pisciculteur/{id}', [PisciculteurController::class, 'update_pisciculteur']);


Route::get("/employe",[EmployeController::class,'get_all_employe']);
Route::post("/employe",[EmployeController::class,'create_employe']);
Route::delete("/employe/{idEmploye}",[EmployeController::class,'delete_employe']);
Route::put('/employe/{idEmploye}', [EmployeController::class, 'update_employe']);

Route::get("/visiteur",[VisiteurController::class,'get_all_visiteur']);
Route::post("/visiteur",[VisiteurController::class,'create_visiteur']);
Route::delete("/visiteur/{idVisiteur}",[VisiteurController::class,'delete_visiteur']);
Route::put('/visiteur/{idVisiteur}', [VisiteurController::class, 'update_visiteur']);





