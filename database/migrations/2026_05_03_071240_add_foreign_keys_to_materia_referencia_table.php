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
        Schema::table('materia_referencia', function (Blueprint $table) {
            $table->foreign(['id_materia'], 'fk_matref_materia')->references(['id_materia'])->on('materias')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['id_referencia'], 'fk_matref_referencia')->references(['id_referencia'])->on('referencias')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materia_referencia', function (Blueprint $table) {
            $table->dropForeign('fk_matref_materia');
            $table->dropForeign('fk_matref_referencia');
        });
    }
};
