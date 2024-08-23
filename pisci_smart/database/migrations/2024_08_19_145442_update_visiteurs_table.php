<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVisiteursTable extends Migration
{
    public function up()
    {
        Schema::table('visiteurs', function (Blueprint $table) {
            // Ajouter le champ password
            $table->string('password')->after('adresse');

            // Ajouter une contrainte d'unicité sur le champ telephone
            $table->unique('telephone');
        });
    }

    public function down()
    {
        Schema::table('visiteurs', function (Blueprint $table) {
            // Supprimer le champ password
            $table->dropColumn('password');

            // Supprimer la contrainte d'unicité sur le champ telephone
            $table->dropUnique(['telephone']);
        });
    }
}
