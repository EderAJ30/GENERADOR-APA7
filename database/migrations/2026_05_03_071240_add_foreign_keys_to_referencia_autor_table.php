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
        Schema::table('referencia_autor', function (Blueprint $table) {
            $table->foreign(['id_autor'], 'fk_refautor_autor')->references(['id_autor'])->on('autores')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['id_referencia'], 'fk_refautor_referencia')->references(['id_referencia'])->on('referencias')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referencia_autor', function (Blueprint $table) {
            $table->dropForeign('fk_refautor_autor');
            $table->dropForeign('fk_refautor_referencia');
        });
    }
};
