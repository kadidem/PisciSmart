<?php

use App\Http\Controllers\NourritureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/getnourritures",[NourritureController::class,'get_all_nourriture']);
Route::post("/create",[NourritureController::class,'create_nourriture']);
Route::delete("/delete/{id}",[NourritureController::class,'delete_nourriture']);
Route::put('/nourritures/{id}', [NourritureController::class, 'update_nourriture']);



