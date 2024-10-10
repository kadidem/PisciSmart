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
        Schema::table('cycles', function (Blueprint $table) {
            // Ajouter la colonne numero_serie qui sera reliée à la table bassins
            $table->string('numero_serie')->nullable();

            // Optionnel : Ajouter une clé étrangère si vous souhaitez une relation explicite
            $table->foreign('numero_serie')->references('numero_serie')->on('bassins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cycles', function (Blueprint $table) {
            // Supprimer la colonne et la clé étrangère
            $table->dropForeign(['numero_serie']);
            $table->dropColumn('numero_serie');
        });
    }
};

