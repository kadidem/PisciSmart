<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Route de base
Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Auth routes
require __DIR__.'/auth.php';

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route pour mettre à jour le mot de passe
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});


