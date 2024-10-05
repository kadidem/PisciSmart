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
        Schema::table('bassins', function (Blueprint $table) {
            // Ajout de la colonne idPisciculteur
            $table->unsignedBigInteger('idPisciculteur')->nullable();

            // Ajout de la contrainte de clé étrangère
            $table->foreign('idPisciculteur')->references('idPisciculteur')->on('pisciculteurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bassins', function (Blueprint $table) {
            // Suppression de la contrainte de clé étrangère
            $table->dropForeign(['idPisciculteur']);

            // Suppression de la colonne idPisciculteur
            $table->dropColumn('idPisciculteur');
        });
    }
};

