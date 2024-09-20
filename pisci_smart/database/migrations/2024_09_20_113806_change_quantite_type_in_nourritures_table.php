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
        Schema::table('nourritures', function (Blueprint $table) {
            $table->string('quantite')->change();
        });
    }

    public function down(): void
    {
        Schema::table('nourritures', function (Blueprint $table) {
            $table->integer('quantite')->change();
        });
    }

};
