<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroSerieToBassinsTable extends Migration
{
    public function up()
    {
        Schema::table('bassins', function (Blueprint $table) {
            // Ajouter la colonne numero_serie et définir la clé étrangère
            $table->string('numero_serie');
            $table->foreign('numero_serie')->references('numero_serie')->on('dispositifs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('bassins', function (Blueprint $table) {
            // Supprimer la colonne numero_serie et la clé étrangère lors du rollback
            $table->dropForeign(['numero_serie']);
            $table->dropColumn('numero_serie');
        });
    }
}

