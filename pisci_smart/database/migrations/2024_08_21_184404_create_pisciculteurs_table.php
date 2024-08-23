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
            $table->id('idpisciculteur');

            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone')->unique();

            $table->string('device_id')->unique();
            $table->string('password');
            $table->rememberToken();

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
