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
        Schema::create('colecciones', function (Blueprint $table) {
            $table->integer('id_coleccion', true);
            $table->integer('id_usuario')->index('fk_colecciones_usuarios');
            $table->integer('id_referencia')->index('fk_colecciones_referencias');
            $table->timestamp('fecha_agregado')->useCurrent();
            $table->text('comentario_personal')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colecciones');
    }
};
