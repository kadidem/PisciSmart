<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdPisciculteurToDispositifsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dispositifs', function (Blueprint $table) {
            // Ajoute la colonne idPisciculteur comme clé étrangère
            $table->unsignedBigInteger('idPisciculteur')->nullable(); // nullable() si tu veux que ce soit optionnel

            // Ajouter la contrainte de clé étrangère
            $table->foreign('idPisciculteur')->references('idPisciculteur')->on('pisciculteurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispositifs', function (Blueprint $table) {
            // Supprimer la clé étrangère et la colonne
            $table->dropForeign(['idPisciculteur']); // Supprime la contrainte de clé étrangère
            $table->dropColumn('idPisciculteur'); // Supprime la colonne
        });
    }
}

