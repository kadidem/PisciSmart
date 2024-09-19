<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAdresseFromPisciculteurs extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            $table->dropColumn('adresse');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            $table->string('adresse')->nullable(); // ou autre définition souhaitée
        });
    }
}

