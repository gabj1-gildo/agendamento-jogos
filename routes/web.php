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

Route::middleware(([CheckSession::class]))->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/loginSubmit', [AuthController::class, 'loginSubmit'])->name('loginSubmit');   

    Route::get('/cadastroUsuario', [AuthController::class, 'cadastroUsuario'])->name('cadastroUsuario');
    Route::post('/salvarUsuario', [AuthController::class, 'salvarUsuario'])->name('salvarUsuario');
});
    
Route::middleware([CheckLogin::class])->group(function () {
        
    Route::get('/', [MainController::class, 'home'])->name('home');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    
    //CADASTRAR E SALVAR JOGOS
    Route::get('/novo_jogo', [JogoController::class, 'novoJogo'])->name('novo_jogo');
    Route::post('/salvar_jogo', [JogoController::class, 'salvarJogo'])->name('salvar_jogo');
    
    //GERENCIAMENTO DE INSCRIÇÕES
    Route::get('/gerenciar_inscricoes', [InscricaoController::class, 'gerenciarInscricoes'])->name('gerenciar_inscricoes');
    Route::get('/nova_inscricao', [InscricaoController::class, 'novaInscricao'])->name('nova_inscricao');
    Route::post('/salvar_inscricao', [InscricaoController::class, 'salvarInscricao'])->name('salvar_inscricao');
    Route::post('/alterar_status_inscricao', [InscricaoController::class, 'alterarStatusInscricao'])->name('alterar_status_inscricao');

    //GERENCIAMENTO DE JOGOS
    Route::get('/gerenciar_jogos', [JogoController::class, 'gerenciarJogos'])->name('gerenciar_jogos');
    Route::get('/editar_jogo', [JogoController::class, 'editarJogo'])->name('editar_jogo');
    Route::post('/atualizar_jogo', [JogoController::class, 'atualizarJogo'])->name('atualizar_jogo');
    Route::get('/cancelar_jogo', [JogoController::class, 'cancelarJogo'])->name('cancelar_jogo');

    // --- GERENCIAMENTO DE LOCAIS ---
    Route::get('/gerenciar_locais', [LocalController::class, 'index'])->name('gerenciar_locais');
    Route::post('/salvar_local', [LocalController::class, 'salvarLocal'])->name('salvar_local');
    Route::post('/atualizar_local', [LocalController::class, 'atualizarLocal'])->name('atualizar_local');

    // --- GERENCIAMENTO DE TÍTULOS ---
    Route::get('/gerenciar_titulos', [TituloController::class, 'index'])->name('gerenciar_titulos');
    Route::post('/salvar_titulo', [TituloController::class, 'salvarTitulo'])->name('salvar_titulo');
    Route::post('/atualizar_titulo', [TituloController::class, 'atualizarTitulo'])->name('atualizar_titulo');
});
    
