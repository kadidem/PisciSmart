<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('cycles', function (Blueprint $table) {
        $table->integer('nombrePoissonsRestants')->default(0); // Champ pour les poissons restants
    });
}

public function down()
{
    Schema::table('cycles', function (Blueprint $table) {
        $table->dropColumn('nombrePoissonsRestants'); // Supprimer le champ lors de la rétrogradation
    });
}

};
