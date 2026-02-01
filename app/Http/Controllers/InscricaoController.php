<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscricao;
use App\Models\Jogo;
use Illuminate\Support\Facades\Crypt;

class InscricaoController extends Controller
{
    //
    // Adicione o parâmetro Request $request
    public function novaInscricao(Request $request) {
        try {
            $id = Crypt::decrypt($request->query('id'));

            // ADICIONE o withCount aqui para carregar a contagem de inscritos
            $jogo = Jogo::with(['titulo', 'local'])
                ->withCount(['inscricoes' => function ($query) {
                    $query->whereIn('status', ['pendente', 'confirmado']); // Conta pendentes ou confirmadas
                }])
                ->findOrFail($id);

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Jogo inválido.');
        }

        return view('nova_inscricao', compact('jogo'));
    }

    public function salvarInscricao(Request $request)
    {
        // 1. Validação básica de existência
        $request->validate([
            'jogo_id' => 'required|exists:jogos,id',
        ], [
            'jogo_id.required' => 'Ocorreu um erro ao identificar o jogo.',
            'jogo_id.exists' => 'O jogo selecionado não existe mais.',
        ]);

        $user_id = session('user_id');
        $jogo_id = $request->input('jogo_id');

        // 2. Buscar o jogo e contar inscrições confirmadas
        $jogo = Jogo::withCount(['inscricoes' => function ($query) {
            $query->where('status', 'confirmado');
        }])->findOrFail($jogo_id);

        // 3. Verificação: Usuário já está inscrito?
        $jaInscrito = Inscricao::where('id_user', $user_id)
                                ->where('id_jogo', $jogo_id)
                                ->exists();

        if ($jaInscrito) {
            return redirect()->back()->with('error', 'Você já se inscreveu neste jogo!');
        }

        // 4. Verificação: Ainda há vagas?
        if (($jogo->vagas - $jogo->inscricoes_count) <= 0) {
            return redirect()->back()->with('error', 'Infelizmente as vagas acabaram enquanto você preenchia o formulário.');
        }

        // 5. Salvar Inscrição
        $inscricao = new Inscricao();
        $inscricao->id_user = $user_id;
        $inscricao->id_jogo = $jogo_id;
        $inscricao->data_inscricao = now();
        $inscricao->save();

        return redirect()->route('home')->with('success', 'Sua inscrição foi enviada com sucesso! Aguarde a confirmação do organizador.');
    }
    
    //GERENCIAR INSCRIÇÕES - ADMIN

   public function gerenciarInscricoes()
    {
        $jogos = Jogo::with(['titulo', 'local', 'responsavel', 'inscricoes.user'])
                    ->where('status', 'aberto')
                    ->get();

        return view('/admin/admin_gerenciar_inscricoes', compact('jogos'));
    }

    public function alterarStatusInscricao(Request $request) 
    {
        $inscricao = Inscricao::findOrFail($request->id_inscricao);
        $statusAnterior = $inscricao->status;
        $novoStatus = $request->status;

        // Se estiver aprovando, fazemos uma última validação de segurança de vagas
        if ($novoStatus === 'confirmado' && $statusAnterior !== 'confirmado') {
            $jogo = Jogo::withCount(['inscricoes' => function($q) {
                $q->where('status', 'confirmado');
            }])->findOrFail($inscricao->id_jogo);

            if ($jogo->inscricoes_count >= $jogo->vagas) {
                return redirect()->back()->with('error', 'Impossível aprovar: O jogo já atingiu o limite de vagas!');
            }
        }

        elseif ($novoStatus === 'cancelada' && $statusAnterior !== 'cancelada') {
            // Se for cancelando, nenhuma verificação extra é necessária
        }
        else {
            return redirect()->back()->with('error', 'Status inválido ou sem alteração.');
        }

        $inscricao->status = $novoStatus;
        $inscricao->save();

        $msg = $novoStatus === 'confirmado' ? 'Inscrição aprovada com sucesso!' : 'Inscrição recusada.';
        return redirect()->back()->with('success', $msg);
    }

}
