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
            $table->id('idRapport');
            $table->foreignId('id')->constrained('cycles')->onDelete('cascade');
            $table->decimal('total_ventes', 10, 2)->default(0);
            $table->decimal('total_depenses', 10, 2)->default(0);
            $table->decimal('total_pertes', 10, 2)->default(0);
            $table->decimal('benefice', 10, 2)->default(0);
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
