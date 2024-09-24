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
        Schema::create('cycles', function (Blueprint $table) {

            $table->id('idCycle'); // Identifiant du cycle
            $table->integer('AgePoisson'); // Âge du poisson en mois
            $table->integer('NbrePoisson'); // Nombre de poissons dans le cycle
            $table->date('DateDebut'); // Date de début du cycle
            $table->date('DateFin'); // Date de fin calculée automatiquement
            $table->integer('NumCycle'); // Numéro du cycle (unique ou autre logique de numérotation)
            $table->string('espece'); // Espèce de poisson (varchar)
            $table->unsignedBigInteger('idBassin'); // colonne pour la clé étrangère
            $table->foreign('idBassin')->references('idBassin')->on('bassins')->onDelete('cascade'); // définition de la clé étrangère

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycles');
    }
};
