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
        Schema::create('pisciculteurs', function (Blueprint $table) {
            $table->id('idPisciculteur');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone')->unique();
            $table->string('adresse');
            $table->string('password');
            $table->timestamps();
            $table->rememberToken();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pisciculteurs');
    }
};
