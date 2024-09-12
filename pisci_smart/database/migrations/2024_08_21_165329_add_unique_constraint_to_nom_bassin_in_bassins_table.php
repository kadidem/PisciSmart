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
            $table->unique('nomBassin');
        });
    }

    public function down(): void
    {
        Schema::table('bassins', function (Blueprint $table) {
            $table->dropUnique(['nomBassin']);
        });
    }

};
