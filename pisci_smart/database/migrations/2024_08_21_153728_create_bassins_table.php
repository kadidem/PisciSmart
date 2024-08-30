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
        Schema::create('bassins', function (Blueprint $table) {
            $table->id('idBassin');
            $table->string('nomBassin');
            $table->string('dimension');
            $table->enum('unite', ['m2', 'm3']); // Unité de mesure (m² ou m³)
            $table->string('description');
            $table->unsignedBigInteger('idDispo');
            $table->foreign('idDispo')->references('idDispo')->on('dispositifs')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bassins');
    }
};
