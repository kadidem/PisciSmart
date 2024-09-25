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
        Schema::table('pisciculteurs', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Supprime la contrainte de clé étrangère avant de supprimer la colonne
            $table->dropColumn('user_id'); // Supprime la colonne user_id
            $table->dropColumn('adresse'); // Supprime la colonne adresse
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->unique(); // Réajoute la colonne user_id
            $table->string('adresse'); // Réajoute la colonne adresse
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Réajoute la clé étrangère
        });
    }
};

