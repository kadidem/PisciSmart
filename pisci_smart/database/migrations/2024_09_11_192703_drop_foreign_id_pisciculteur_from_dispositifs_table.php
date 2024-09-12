<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignIdPisciculteurFromDispositifsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispositifs', function (Blueprint $table) {
            // Supprimer d'abord la contrainte de clé étrangère
            $table->dropForeign(['idPisciculteur']);

            // Ensuite, supprimer la colonne si nécessaire
            $table->dropColumn('idPisciculteur');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispositifs', function (Blueprint $table) {
            // Ajouter de nouveau la colonne et la clé étrangère en cas de rollback
            $table->unsignedBigInteger('idPisciculteur');

            $table->foreign('idPisciculteur')->references('id')->on('pisciculteurs')
                ->onDelete('cascade');
        });
    }
}
