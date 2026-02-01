<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    /*id 
    nome 
    email
    telefone
    senha
    tipo de usuario (admin ou usuario) */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('nome', 100)->nullable();
            $table->string('email', 150)->unique();
            $table->string('telefone', 20)->nullable();
            $table->string('senha', 150);
            $table->enum('tipo_usuario', ['usuario', 'admin'])->default('usuario');
            $table->datetime('ultimo_login')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
