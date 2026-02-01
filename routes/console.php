<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use App\Models\Jogo;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    // Procura jogos abertos que já passaram da data limite
    Jogo::where('status', 'aberto')
        ->where('data_hora_limite_inscricao', '<=', now())
        ->update(['status' => 'encerrado']);
})->everyMinute();