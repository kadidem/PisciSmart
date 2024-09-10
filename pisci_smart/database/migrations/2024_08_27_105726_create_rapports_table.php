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
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCycle'); // Référence au cycle
            $table->integer('nombre_ventes')->default(0);
            $table->decimal('montant_total_ventes', 15, 2)->default(0);
            $table->integer('nombre_depenses')->default(0);
            $table->decimal('montant_total_depenses', 15, 2)->default(0);
            $table->integer('nombre_pertes')->default(0);
            $table->decimal('montant_total_pertes', 15, 2)->default(0);
            $table->decimal('benefice', 15, 2)->default(0); // Bénéfice calculé
            $table->foreign('idCycle')->references('idCycle')->on('cycles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
