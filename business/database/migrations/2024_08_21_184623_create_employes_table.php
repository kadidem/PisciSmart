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

            $table->id('idemploye');
        $table->string('nom');
        $table->string('prenom');
        $table->string('telephone')->unique();

        $table->unsignedBigInteger('idpisciculteur');
        $table->string('password');
        $table->rememberToken();

        $table->foreign('idpisciculteur')->references('idpisciculteur')->on('pisciculteurs')->onDelete('cascade');
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
