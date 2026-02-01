<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jogo;
use App\Models\Local;
use App\Models\Titulo;
use Illuminate\Support\Facades\Crypt;

class JogoController extends Controller
{
    public function novoJogo() {
        // Busca todos os locais do banco de dados
        $locais = Local::all();
        $titulos = Titulo::all();

        // Envia a variável $locais para a view 'novo_jogo
        return view('novo_jogo', compact('locais', 'titulos'));
    }

    public function salvarJogo(Request $request) {
        // 1. Valide os campos que REALMENTE vêm do formulário
        $request->validate([
            'data' => 'required|date',
            'hora' => 'required',
            'local' => 'required',
            'limite_jogadores' => 'required|integer|min:1',
            'descricao' => 'required|string',
            'data_limite_inscricao' => 'required|date',
            'hora_limite_inscricao' => 'required',
            'status' => 'required',
        ], [
            'data.required' => 'A data do jogo é obrigatória.',
            'limite_jogadores.min' => 'O jogo deve ter pelo menos 1 vaga.',
            // adicione outras mensagens personalizadas aqui se desejar
        ]);

        $user_id = session('user_id');
        
        // 2. Formatação das datas
        $data_hora = $request->input('data') . ' ' . $request->input('hora');
        $data_hora_limite = $request->input('data_limite_inscricao') . ' ' . $request->input('hora_limite_inscricao');

        // 3. Salvamento
        Jogo::create([
            'responsavel_id' => $user_id,
            'titulo_id' => $request->input('titulo'),
            'data_hora' => $data_hora,
            'local_id' => $request->input('local'),
            'vagas' => $request->input('limite_jogadores'),
            'vagas_disponiveis' => $request->input('limite_jogadores'),
            'descricao' => $request->input('descricao'),
            'data_hora_limite_inscricao' => $data_hora_limite,
            'status' => $request->input('status'),
        ]);

        return redirect()->route('home')->with('success', 'Jogo criado com sucesso!');
    }
    
    //GERENCIAR JOGOS - ADMIN E ORGANIZADOR
    public function gerenciarJogos()
    {
        $user_id = session('user_id');
        $user_tipo = session('user_tipo');

        $query = Jogo::with(['titulo', 'local', 'responsavel'])->withCount('inscricoes');

        // Se não for admin master, filtra para ver apenas os próprios jogos
        if ($user_tipo !== 'admin') {
            $query->where('responsavel_id', $user_id);
        }

        $jogosAgrupados = $query->get()->groupBy(function($item) {
            return $item->responsavel->nome ?? 'Sem Organizador';
        });

        return view('/admin/admin_gerenciar_jogos', compact('jogosAgrupados'));
    }

    public function editarJogo(Request $request)
    {
        try {
            // Pega o ID da URL (?id=...) e descriptografa
            $id = Crypt::decrypt($request->query('id'));
            $jogo = Jogo::findOrFail($id);

            // Busca os dados para os selects (Titulos e Locais)
            $titulos = Titulo::all();
            $locais = Local::all();

            // IMPORTANTE: Passar a variável 'jogo' (no singular)
            return view('/admin/admin_editar_jogo', compact('jogo', 'titulos', 'locais'));
        } catch (\Exception $e) {
            return redirect()->route('gerenciar_jogos')->with('error', 'Erro ao carregar o jogo.');
        }
    }

    public function atualizarJogo(Request $request)
    {
        // 1. Descriptografia e Validação Inicial
        try {
            $id = Crypt::decrypt($request->input('id'));
        } catch (\Exception $e) {
            return redirect()->route('gerenciar_jogos')->with('error', 'ID inválido.');
        }

        $request->validate([
            'data_hora' => 'required|date',
            'data_hora_limite_inscricao' => 'required|date',
            // ... outras validações ...
        ]);

        // 2. Lógica de Comparação de Datas
        $data_jogo = strtotime($request->data_hora);
        $data_limite = strtotime($request->data_hora_limite_inscricao);
        $agora = time();

        // Verificação 1: Data limite deve ser maior que o horário atual
        if ($data_limite <= $agora) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['data_hora_limite_inscricao' => 'A data limite de inscrição deve ser um horário futuro.']);
        }

        // Verificação 2: Data limite deve ser menor ou igual à data do jogo
        if ($data_limite > $data_jogo) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['data_hora_limite_inscricao' => 'A data limite não pode ser posterior à data do início do jogo.']);
        }

        // 3. Atualização no Banco
        $jogo = Jogo::findOrFail($id);
        
        // Verificação de permissão (opcional, mas recomendada)
        if (session('user_tipo') !== 'admin' && $jogo->responsavel_id !== session('user_id')) {
            return redirect()->route('gerenciar_jogos')->with('error', 'Sem permissão.');
        }

        $jogo->update([
            'titulo_id' => $request->titulo_id,
            'local_id'  => $request->local_id,
            'data_hora' => $request->data_hora,
            'data_hora_limite_inscricao' => $request->data_hora_limite_inscricao,
            'vagas'     => $request->vagas,
            'descricao' => $request->descricao,
            'status'    => $request->status,
        ]);

        return redirect()->route('gerenciar_jogos')->with('success', 'Jogo e prazos atualizados com sucesso!');
    }

    // 6. ALTERAR STATUS (CANCELAR/ENCERRAR)
    public function cancelarJogo(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->query('id'));
            $jogo = Jogo::findOrFail($id);

            // ADICIONE ESTA VALIDAÇÃO:
            if (session('user_tipo') !== 'admin' && $jogo->responsavel_id !== session('user_id')) {
                return redirect()->back()->with('error', 'Sem permissão para alterar este status.');
            }

            $jogo->status = ($jogo->status == 'aberto') ? 'encerrado' : 'aberto';
            $jogo->save();

            return redirect()->back()->with('success', 'Status do vôlei atualizado!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao processar a solicitação.');
        }
    }
}
