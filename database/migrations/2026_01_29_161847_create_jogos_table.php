<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    /* Tabela de partidas de volei 
    id unico e auto incremental
    responsavel pela partida (user_id tem que ser admin)
    data da partida
    hora de inicio
    vagas
    descricao
    local_id (chave estrangeira para tabela locais)
    data limite de inscricao
    hora limite de inscricao
    status (aberto, encerrado, cancelado)
    */
    public function up(): void
    {
       Schema::create('jogos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsavel_id')->constrained('users');
            $table->datetime('data_hora');
            $table->integer('vagas')->unsigned();
            $table->integer('vagas_disponiveis')->unsigned();
            //titulo do jogo com chave estrangeira
            $table->foreignId('titulo_id')->constrained('titulos', 'id');
            $table->text('descricao')->nullable();
            $table->foreignId('local_id')->constrained('locais');
            $table->datetime('data_hora_limite_inscricao');
            $table->string('status', 20);
            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jogos');
    }
};
