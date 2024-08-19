<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PisciculteurController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\NourritureController;
use App\Http\Controllers\VisiteurController;


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


