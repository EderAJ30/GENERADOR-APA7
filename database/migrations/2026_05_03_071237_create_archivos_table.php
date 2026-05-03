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
        Schema::create('archivos', function (Blueprint $table) {
            $table->integer('id_archivo', true);
            $table->integer('id_referencia')->index('fk_archivos_referencias');
            $table->string('nombre_archivo');
            $table->string('ruta_storage', 500)->unique('ruta_storage');
            $table->string('formato', 10)->default('pdf');
            $table->bigInteger('tamano_bytes');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivos');
    }
};
