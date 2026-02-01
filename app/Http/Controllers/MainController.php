<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jogo;
use App\Models\Local;
use Illuminate\Support\Facades\Crypt;

class MainController extends Controller
{
    //
   public function home()
    {
        $jogos = Jogo::with(['titulo', 'local'])
        
                    // Conta as inscrições confirmadas e pendentes de forma eficiente
                    ->withCount(['inscricoes' => function ($query) {
                        $query->whereIn('status', ['pendente', 'confirmado']);
                    }])

                    ->where('status', 'aberto')
                    ->where('data_hora_limite_inscricao', '>', now()) // Só mostra se a data atual for menor que a limite
                    ->orderBy('data_hora', 'asc')
                    ->get();

        return view('home', compact('jogos'));
    }
}
