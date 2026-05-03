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
        Schema::create('referencia_autor', function (Blueprint $table) {
            $table->integer('id_referencia');
            $table->integer('id_autor')->index('fk_refautor_autor');
            $table->tinyInteger('orden')->default(1);
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['id_referencia', 'id_autor']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referencia_autor');
    }
};
