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
        Schema::table('nourritures', function (Blueprint $table) {
            $table->time('heure')->after('date'); // Ajoute la colonne 'heure' après la colonne 'date'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nourritures', function (Blueprint $table) {
            $table->dropColumn('heure'); // Supprime la colonne 'heure' si la migration est annulée
        });
    }
};

