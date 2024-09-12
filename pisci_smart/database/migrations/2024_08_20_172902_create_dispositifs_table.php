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
        Schema::create('dispositifs', function (Blueprint $table) {
            $table->id('idDispo');
            $table->string('num')->unique();
            $table->string('longitude');
            $table->string('latitude');
            $table->unsignedBigInteger('idPisciculteur');
            $table->foreign('idPisciculteur')->references('idPisciculteur')->on('pisciculteurs')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositifs');
    }
};
