<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyIdPisciculteurInBassinsTable extends Migration
{
    public function up()
    {
        Schema::table('bassins', function (Blueprint $table) {
            // Modifier la colonne idPisciculteur pour qu'elle ne soit pas nullable
            $table->unsignedBigInteger('idPisciculteur')->change();
        });
    }

    public function down()
    {
        Schema::table('bassins', function (Blueprint $table) {
            // Rétablir la colonne comme nullable lors du rollback
            $table->unsignedBigInteger('idPisciculteur')->nullable()->change();
        });
    }
}
