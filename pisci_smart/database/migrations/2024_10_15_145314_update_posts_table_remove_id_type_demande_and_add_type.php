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
        Schema::table('posts', function (Blueprint $table) {
            // Supprimer la colonne idTypeDemande
            $table->dropForeign(['idTypeDemande']);
            $table->dropColumn('idTypeDemande');

            // Ajouter un champ type
            $table->string('type')->after('user_id'); // Vous pouvez utiliser string ou enum selon vos besoins
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Restaurer la colonne idTypeDemande
            $table->foreignId('idTypeDemande')->constrained('type_demandes', 'idTypeDemande')->onDelete('cascade');

            // Supprimer le champ type
            $table->dropColumn('type');
        });
    }
};

