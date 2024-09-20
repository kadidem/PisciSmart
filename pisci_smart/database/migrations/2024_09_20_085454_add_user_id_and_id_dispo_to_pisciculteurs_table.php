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
        Schema::table('pisciculteurs', function (Blueprint $table) {
            // Ajout des colonnes et des clés étrangères
            $table->unsignedBigInteger('user_id')->unique()->after('idPisciculteur');
            $table->unsignedBigInteger('idDispo')->after('user_id');

            // Clé étrangère pour user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Clé étrangère pour idDispo
            $table->foreign('idDispo')->references('idDispo')->on('dispositifs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            // Suppression des relations et des colonnes
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->dropForeign(['idDispo']);
            $table->dropColumn('idDispo');
        });
    }
};

