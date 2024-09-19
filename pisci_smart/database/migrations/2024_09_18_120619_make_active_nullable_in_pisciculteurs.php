<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeActiveNullableInPisciculteurs extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            $table->boolean('active')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pisciculteurs', function (Blueprint $table) {
            $table->boolean('active')->default(1)->change();
        });
    }
}
