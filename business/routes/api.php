<?php

use App\Http\Controllers\CycleController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\PerteController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\VenteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource('cycles', CycleController::class);
Route::apiResource('ventes', VenteController::class);
Route::apiResource('depenses', DepenseController::class);
Route::apiResource('pertes', PerteController::class);
Route::apiResource('rapports', RapportController::class);

Route::get('rapports/generate/{id}', [RapportController::class, 'generate']);
Route::get('rapports/{id}', [RapportController::class, 'show']);


// ->only(['index','show', 'store','update',])
