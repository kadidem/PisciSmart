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
        Schema::table('cycles', function (Blueprint $table) {
            // Modifier la colonne numero_serie pour qu'elle ne soit plus nullable
            $table->string('numero_serie')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cycles', function (Blueprint $table) {
            // Rendre la colonne à nouveau nullable si besoin lors de la réversion
            $table->string('numero_serie')->nullable()->change();
        });
    }
};
