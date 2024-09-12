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
        Schema::table('bassins', function (Blueprint $table) {
            $table->date('date')->after('description'); // Ajoute une colonne date après description
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bassins', function (Blueprint $table) {
            $table->dropColumn('date'); // Supprime la colonne date
        });
    }
};

