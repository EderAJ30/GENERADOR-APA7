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
        Schema::create('materia_referencia', function (Blueprint $table) {
            $table->integer('id_materia');
            $table->integer('id_referencia')->index('fk_matref_referencia');
            $table->string('tipo_bibliografia', 20)->default('basica');
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['id_materia', 'id_referencia']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_referencia');
    }
};
