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
        Schema::create('posts', function (Blueprint $table) {
            $table->id('idPost');
            $table->foreignId('idPisciculteur')->nullable()->constrained('pisciculteurs', 'idPisciculteur')->onDelete('cascade');
            $table->foreignId('idVisiteur')->nullable()->constrained('visiteurs', 'idVisiteur')->onDelete('cascade');
            $table->foreignId('idTypeDemande')->constrained('type_demandes', 'idTypeDemande')->onDelete('cascade');
            $table->text('contenu');
            $table->timestamps();
        
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
