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
        Schema::table('posts', function (Blueprint $table) {
            // Supprimer les colonnes idPisciculteur et idVisiteur
            $table->dropForeign(['idPisciculteur']);
            $table->dropColumn('idPisciculteur');

            $table->dropForeign(['idVisiteur']);
            $table->dropColumn('idVisiteur');

            // Ajouter la colonne user_id qui référence la table users
            $table->foreignId('user_id')->after('idPost')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Restaurer les colonnes idPisciculteur et idVisiteur
            $table->foreignId('idPisciculteur')->nullable()->constrained('pisciculteurs', 'idPisciculteur')->onDelete('cascade');
            $table->foreignId('idVisiteur')->nullable()->constrained('visiteurs', 'idVisiteur')->onDelete('cascade');

            // Supprimer la colonne user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};

