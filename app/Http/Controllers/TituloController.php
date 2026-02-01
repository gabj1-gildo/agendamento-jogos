<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Titulo;

class TituloController extends Controller
{
    public function index()
    {
        $titulos = Titulo::all();
        return view('/admin/admin_gerenciar_titulos', compact('titulos'));
    }

    public function salvarTitulo(Request $request)
    {
        $request->validate(['nome' => 'required|string|max:255']);

        Titulo::create(['nome' => $request->nome]);

        return redirect()->route('gerenciar_titulos')->with('success', 'Título cadastrado com sucesso!');
    }

    public function atualizarTitulo(Request $request)
    {
        // Verificação de Admin Master (ajuste 'admin' para o valor que você usa na sua sessão)
        if (session('user_tipo') !== 'admin') {
            return redirect()->route('gerenciar_titulos')->with('error', 'Apenas o Admin Master pode alterar títulos.');
        }

        $request->validate([
            'id' => 'required|exists:titulos,id',
            'nome' => 'required|string|max:255'
        ]);

        $titulo = Titulo::findOrFail($request->id);
        $titulo->update(['nome' => $request->nome]);

        return redirect()->route('gerenciar_titulos')->with('success', 'Título atualizado com sucesso!');
    }
}