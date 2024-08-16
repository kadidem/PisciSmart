<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Routes pour les posts
Route::get('/posts', [PostController::class, 'index']);              // GET : Récupérer tous les posts
Route::post('/posts', [PostController::class, 'store']);            // POST : Créer un nouveau post
Route::get('/posts/{post}', [PostController::class, 'show']);       // GET : Afficher un post spécifique
Route::delete('posts/{post}', [PostController::class, 'destroy']); // DELETE : Supprimer un post

// Routes pour les commentaires
Route::get('/posts/{post}/comments', [CommentController::class, 'index']);    // GET : Récupérer les commentaires d'un post
Route::post('/posts/{post}/comments', [CommentController::class, 'store']);   // POST : Ajouter un commentaire à un post

Route::get('/comments/{comment}', [CommentController::class, 'show']);        // GET : Afficher un commentaire spécifique
Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);  // DELETE : Supprimer un commentaire

// Routes pour les likes
Route::post('/posts/{post}/like', [LikeController::class, 'store']);          // POST : Aimer un post
Route::delete('posts/{post}/like', [LikeController::class, 'destroy']);      // DELETE : Ne plus aimer un post

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');