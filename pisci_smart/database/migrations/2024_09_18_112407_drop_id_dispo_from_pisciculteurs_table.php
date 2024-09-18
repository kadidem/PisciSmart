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
            $table->dropColumn('idDispo'); // Supprime la colonne idDispo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            $table->unsignedBigInteger('idDispo'); // Réajoute la colonne idDispo si nécessaire
        });
    }
};
