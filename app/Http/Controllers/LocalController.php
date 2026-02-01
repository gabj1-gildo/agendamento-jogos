<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Local;

class LocalController extends Controller
{
    /**
     * Lista todos os locais cadastrados.
     * View: admin_locais.blade.php
     */
    public function index()
    {
        // Busca todos os locais do banco de dados
        $locais = Local::all();
        
        return view('/admin/admin_gerenciar_locais', compact('locais'));
    }

    /**
     * Salva um novo local no banco de dados.
     */
    public function salvarLocal(Request $request)
    {
        // Validação dos dados recebidos do modal de cadastro
        $request->validate([
            'nome'     => 'required|string|max:255',
            'endereco' => 'nullable|string|max:255',
            'tipo'     => 'required|in:publico,privado',
        ], [
            'nome.required' => 'O nome do local é obrigatório.',
            'tipo.required' => 'O tipo de acesso deve ser selecionado.',
        ]);

        // Criação do registro
        Local::create([
            'nome'     => $request->input('nome'),
            'endereco' => $request->input('endereco'),
            'tipo'     => $request->input('tipo'),
        ]);

        return redirect()->route('gerenciar_locais')->with('success', 'Local cadastrado com sucesso!');
    }

    /**
     * Atualiza APENAS o tipo (público/privado) de um local existente.
     */
    public function atualizarLocal(Request $request)
    {
        // Validação: verifica se o ID existe e se o tipo é válido
        $request->validate([
            'id'   => 'required|exists:locais,id',
            'tipo' => 'required|in:publico,privado',
        ]);

        try {
            $local = Local::findOrFail($request->input('id'));
            
            // Atualização conforme a regra de negócio solicitada
            $local->update([
                'tipo' => $request->input('tipo')
            ]);

            return redirect()->route('gerenciar_locais')->with('success', 'Tipo de acesso atualizado com sucesso!');
            
        } catch (\Exception $e) {
            return redirect()->route('gerenciar_locais')->with('error', 'Erro ao tentar atualizar o local.');
        }
    }
}