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
    id_user
    id_volei
    data_inscricao
    status (confirmada, cancelada, pendente (padrão))
     */
    public function up(): void
    {
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_jogo')->constrained('jogos');
            $table->datetime('data_inscricao');
            $table->enum('status', ['confirmada', 'cancelada', 'pendente'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscricoes');
    }
};
