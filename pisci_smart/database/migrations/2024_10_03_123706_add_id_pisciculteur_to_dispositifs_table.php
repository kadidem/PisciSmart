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
        Schema::table('dispositifs', function (Blueprint $table) {
            $table->unsignedBigInteger('idPisciculteur')->nullable();

            // Si idPisciculteur est une clé étrangère
            $table->foreign('idPisciculteur')->references('idPisciculteur')->on('pisciculteurs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('dispositifs', function (Blueprint $table) {
            $table->dropForeign(['idPisciculteur']);
            $table->dropColumn('idPisciculteur');
        });
    }

};
