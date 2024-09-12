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
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id('idCommentaire');
            $table->foreignId('idPost')->constrained('posts', 'idPost')->onDelete('cascade');
            $table->foreignId('idPisciculteur')->nullable()->constrained('pisciculteurs', 'idPisciculteur')->onDelete('set null');
            $table->foreignId('idVisiteur')->nullable()->constrained('visiteurs', 'idVisiteur')->onDelete('set null');
            $table->text('contenu'); // Utilisez `text` pour des commentaires plus longs

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};

