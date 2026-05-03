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
        Schema::table('referencias', function (Blueprint $table) {
            $table->foreign(['id_editorial'], 'fk_referencias_editoriales')->references(['id_editorial'])->on('editoriales')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_tipo_referencia'], 'fk_referencias_tipos')->references(['id_tipo_referencia'])->on('tipos_referencia')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_usuario'], 'fk_referencias_usuarios')->references(['id_usuario'])->on('usuarios')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referencias', function (Blueprint $table) {
            $table->dropForeign('fk_referencias_editoriales');
            $table->dropForeign('fk_referencias_tipos');
            $table->dropForeign('fk_referencias_usuarios');
        });
    }
};
