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
        Schema::create('pertes', function (Blueprint $table) {
            $table->id('idPerte');
            $table->integer('NbreMort');
            $table->date('Date');
            $table->unsignedBigInteger('idCycle'); // colonne pour la clé étrangère
            $table->foreign('idCycle')->references('idCycle')->on('cycles')->onDelete('cascade'); // définition de la clé étrangère
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertes');
    }
};
