<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            // Ajouter des contraintes d'unicité pour éviter les duplications
            $table->unique(['idPost', 'idPisciculteur'], 'unique_post_pisciculteur');
            $table->unique(['idPost', 'idVisiteur'], 'unique_post_visiteur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            // Supprimer les contraintes d'unicité si besoin
            $table->dropUnique('unique_post_pisciculteur');
            $table->dropUnique('unique_post_visiteur');
        });
    }
};

