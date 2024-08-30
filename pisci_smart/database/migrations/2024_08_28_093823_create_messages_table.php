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
        Schema::create('messages', function (Blueprint $table) {
            $table->id('idMessage');

            // Colonnes pour l'expéditeur
            $table->foreignId('expediteur_pisciculteur_id')->nullable()->constrained('pisciculteurs', 'idPisciculteur')->onDelete('cascade');
            $table->foreignId('expediteur_visiteur_id')->nullable()->constrained('visiteurs', 'idVisiteur')->onDelete('cascade');

            // Colonnes pour le destinataire
            $table->foreignId('destinataire_pisciculteur_id')->nullable()->constrained('pisciculteurs', 'idPisciculteur')->onDelete('cascade');
            $table->foreignId('destinataire_visiteur_id')->nullable()->constrained('visiteurs', 'idVisiteur')->onDelete('cascade');

            // Contenu du message
            $table->text('contenu');

            // Indique si le message a été lu
            $table->boolean('lu')->default(false);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
