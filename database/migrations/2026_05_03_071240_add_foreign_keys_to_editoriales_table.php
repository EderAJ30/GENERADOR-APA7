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
        Schema::table('editoriales', function (Blueprint $table) {
            $table->foreign(['id_pais'], 'fk_editoriales_paises')->references(['id_pais'])->on('paises')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('editoriales', function (Blueprint $table) {
            $table->dropForeign('fk_editoriales_paises');
        });
    }
};
