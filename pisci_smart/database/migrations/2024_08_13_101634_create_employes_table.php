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
        Schema::create('employes', function (Blueprint $table) {
            $table->id('idEmploye');
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone');
            $table->string('adresse');
            $table->unsignedBigInteger('idPisciculteur');
            $table->foreign('idPisciculteur')->references('idPisciculteur')->on('pisciculteurs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
