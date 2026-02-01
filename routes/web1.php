<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\JogoController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\TituloController;
use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\CheckSession;


Route::get('/', [MainController::class, 'home'])->name('home');

Route::middleware([CheckLogin::class])->group(function () {
    
    // --- ACESSO PARA TODOS OS LOGADOS (Usuario, Organizador, Admin) ---
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/nova_inscricao', [InscricaoController::class, 'novaInscricao'])->name('nova_inscricao');
    Route::post('/salvar_inscricao', [InscricaoController::class, 'salvarInscricao'])->name('salvar_inscricao');

    // --- ACESSO PARA ORGANIZADOR E ADMIN ---
    Route::middleware(['nivel:organizador,admin'])->group(function () {
        Route::get('/novo_jogo', [JogoController::class, 'novoJogo'])->name('novo_jogo');
        Route::post('/salvar_jogo', [JogoController::class, 'salvarJogo'])->name('salvar_jogo');
        Route::get('/gerenciar_jogos', [JogoController::class, 'gerenciarJogos'])->name('gerenciar_jogos');
        Route::get('/editar_jogo', [JogoController::class, 'editarJogo'])->name('editar_jogo');
        Route::post('/atualizar_jogo', [JogoController::class, 'atualizarJogo'])->name('atualizar_jogo');
        Route::get('/cancelar_jogo', [JogoController::class, 'cancelarJogo'])->name('cancelar_jogo');
        
        Route::get('/gerenciar_inscricoes', [InscricaoController::class, 'gerenciarInscricoes'])->name('gerenciar_inscricoes');
        Route::post('/alterar_status_inscricao', [InscricaoController::class, 'alterarStatusInscricao'])->name('alterar_status_inscricao');
    });

    // --- ACESSO EXCLUSIVO PARA ADMIN ---
    Route::middleware(['nivel:admin'])->group(function () {
        // Gerenciamento de Locais
        Route::get('/gerenciar_locais', [LocalController::class, 'index'])->name('gerenciar_locais');
        Route::post('/salvar_local', [LocalController::class, 'salvarLocal'])->name('salvar_local');
        Route::post('/atualizar_local', [LocalController::class, 'atualizarLocal'])->name('atualizar_local');

        // Gerenciamento de Títulos
        Route::get('/gerenciar_titulos', [TituloController::class, 'index'])->name('gerenciar_titulos');
        Route::post('/salvar_titulo', [TituloController::class, 'salvarTitulo'])->name('salvar_titulo');
        Route::post('/atualizar_titulo', [TituloController::class, 'atualizarTitulo'])->name('atualizar_titulo');
    });
});