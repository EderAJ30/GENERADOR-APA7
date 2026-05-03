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
        Schema::create('referencia_tema', function (Blueprint $table) {
            $table->integer('id_referencia');
            $table->integer('id_tema')->index('fk_reftema_tema');
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['id_referencia', 'id_tema']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referencia_tema');
    }
};
