<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIddispoFromPisciculteursTable extends Migration
{
    public function up(): void
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            if (Schema::hasColumn('pisciculteurs', 'idDispo')) { // Vérifie si la colonne existe
                $table->dropColumn('idDispo'); // Supprime la colonne idDispo
            }
        });
    }

    public function down(): void
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            $table->unsignedBigInteger('idDispo')->nullable(); // Ajoute la colonne si besoin
            // Optionnel : Rétablis la clé étrangère ici si elle doit être recréée
            //$table->foreign('idDispo')->references('idDispo')->on('dispositifs');

        });
    }
}


