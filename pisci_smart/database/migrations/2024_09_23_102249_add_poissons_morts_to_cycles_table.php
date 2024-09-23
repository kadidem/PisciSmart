<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPoissonsMortsToCyclesTable extends Migration
{
    public function up()
    {
        Schema::table('cycles', function (Blueprint $table) {
            $table->integer('poissons_morts')->nullable()->after('NbrePoisson');
        });
    }

    public function down()
    {
        Schema::table('cycles', function (Blueprint $table) {
            $table->dropColumn('poissons_morts');
        });
    }
}

