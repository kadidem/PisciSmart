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
            // Ajouter le champ image
            $table->string('image')->nullable()->after('contenu');

            // Rendre le champ contenu nullable
            $table->text('contenu')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Supprimer le champ image
            $table->dropColumn('image');

            // Revenir à contenu non-nullable
            $table->text('contenu')->nullable(false)->change();
        });
    }
};

