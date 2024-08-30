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
        Schema::create('likes', function (Blueprint $table) {
            $table->id('idLike');
            $table->foreignId('idPost')->nullable()->constrained('posts', 'idPost')->onDelete('cascade');
            $table->foreignId('idPisciculteur')->nullable()->constrained('pisciculteurs', 'idPisciculteur')->onDelete('cascade');
            $table->foreignId('idVisiteur')->nullable()->constrained('visiteurs', 'idVisiteur')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
