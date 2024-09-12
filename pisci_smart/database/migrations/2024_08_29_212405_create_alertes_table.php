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
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idPisciculteur')->nullable();
            $table->unsignedBigInteger('idEmploye')->nullable();
            $table->text('message');
            $table->foreign('idPisciculteur')->references('idPisciculteur')->on('pisciculteurs')->onDelete('cascade');
            $table->foreign('idEmploye')->references('idEmploye')->on('employes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
