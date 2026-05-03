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
        Schema::table('colecciones', function (Blueprint $table) {
            $table->foreign(['id_referencia'], 'fk_colecciones_referencias')->references(['id_referencia'])->on('referencias')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['id_usuario'], 'fk_colecciones_usuarios')->references(['id_usuario'])->on('usuarios')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colecciones', function (Blueprint $table) {
            $table->dropForeign('fk_colecciones_referencias');
            $table->dropForeign('fk_colecciones_usuarios');
        });
    }
};
