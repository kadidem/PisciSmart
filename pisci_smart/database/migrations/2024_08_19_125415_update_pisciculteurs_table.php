<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePisciculteursTable extends Migration
{
    public function up()
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            // Ajouter le champ password
            $table->string('password')->after('adresse');

            // Ajouter une contrainte d'unicité sur le champ telephone
            $table->unique('telephone');
        });
    }

    public function down()
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            // Supprimer le champ password
            $table->dropColumn('password');

            // Supprimer la contrainte d'unicité sur le champ telephone
            $table->dropUnique(['telephone']);
        });
    }
}

