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
        Schema::table('referencia_tema', function (Blueprint $table) {
            $table->foreign(['id_referencia'], 'fk_reftema_referencia')->references(['id_referencia'])->on('referencias')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['id_tema'], 'fk_reftema_tema')->references(['id_tema'])->on('temas')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referencia_tema', function (Blueprint $table) {
            $table->dropForeign('fk_reftema_referencia');
            $table->dropForeign('fk_reftema_tema');
        });
    }
};
