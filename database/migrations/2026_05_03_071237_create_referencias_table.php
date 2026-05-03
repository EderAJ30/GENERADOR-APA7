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
        Schema::create('referencias', function (Blueprint $table) {
            $table->integer('id_referencia', true);
            $table->integer('id_tipo_referencia')->index('fk_referencias_tipos');
            $table->integer('id_usuario')->index('fk_referencias_usuarios');
            $table->integer('id_editorial')->nullable()->index('fk_referencias_editoriales');
            $table->string('titulo')->index('idx_referencias_titulo');
            $table->smallInteger('anio_publicacion');
            $table->date('fecha_exacta')->nullable();
            $table->string('volumen', 20)->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('paginas', 50)->nullable();
            $table->string('isbn_issn', 20)->nullable();
            $table->string('doi', 100)->nullable()->unique('doi');
            $table->string('url', 500)->nullable();
            $table->text('resumen')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referencias');
    }
};
