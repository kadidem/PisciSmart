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
        Schema::table('dispositifs', function (Blueprint $table) {
            // Ajouter la colonne 'numero_serie' de 4 caractères et unique
            $table->string('numero_serie', 4)->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispositifs', function (Blueprint $table) {
             // Supprimer la colonne 'numero_serie'
             $table->dropColumn('numero_serie');
        });
    }
};
