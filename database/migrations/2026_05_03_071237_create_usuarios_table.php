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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('id_usuario', true);
            $table->string('nombre_usuario', 100);
            $table->string('paterno_usuario', 100);
            $table->string('materno_usuario', 100);
            $table->string('email', 100)->unique('email');
            $table->rememberToken();
            $table->boolean('estatus')->default(true);
            $table->string('password');
            $table->integer('rol_id')->nullable()->index('fk_usuarios_roles');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
