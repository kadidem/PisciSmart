<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_timestamps_to_type_demandes_table.php
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
        Schema::table('type_demandes', function (Blueprint $table) {
            $table->timestamps(); // Ajoute les colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('type_demandes', function (Blueprint $table) {
            $table->dropTimestamps(); // Supprime les colonnes created_at et updated_at
        });
    }
};

