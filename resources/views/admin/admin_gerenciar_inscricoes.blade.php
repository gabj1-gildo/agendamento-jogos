@extends('layouts.main_layout')

@section('content')
<div class="container mt-5">
    @include('top_bar')
    
    <h2 class="mb-4 text-center">Gerenciar Inscrições</h2>

    {{-- Exibição de Alertas de Sucesso ou Erro --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="accordion" id="accordionJogos">
        @foreach($jogos as $jogo)
            <div class="accordion-item mb-3 border-0 shadow-sm">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $jogo->id }}">
                        <div class="d-flex flex-column w-100">
                            <div class="d-flex justify-content-between me-3">
                                <span class="fw-bold fs-5">{{ $jogo->titulo->nome }}</span>
                                <span class="badge bg-primary">{{ $jogo->inscricoes->count() }} inscritos</span>
                            </div>
                            {{-- Informações de Local e Organizador no Cabeçalho --}}
                            <div class="text-muted small mt-1">
                                <i class="bi bi-geo-alt-fill"></i> Local: {{ $jogo->local->nome }} | 
                                <i class="bi bi-person-badge"></i> Organizador: {{ $jogo->responsavel->nome ?? 'Sem Organizador' }} |
                                <i class="bi bi-calendar3"></i> {{ date('d/m/Y H:i', strtotime($jogo->data_hora)) }}
                            </div>
                        </div>
                    </button>
                </h2>
                
                <div id="collapse{{ $jogo->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionJogos">
                    <div class="accordion-body bg-white">
                        {{-- Detalhes extras do Local --}}
                        <div class="alert alert-light border mb-4 py-2">
                            <strong>Endereço do Local:</strong> {{ $jogo->local->endereco ?? 'Não informado' }}
                        </div>

                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Ordem</th>
                                    <th>Jogador</th>
                                    <th>Data Inscrição</th>
                                    <th>Status</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jogo->inscricoes as $index => $inscricao)
                                    <tr>
                                        <td>{{ $index + 1 }}º</td>
                                        <td><strong>{{ $inscricao->user->nome }}</strong></td>
                                        <td>{{ $inscricao->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge {{ $inscricao->status == 'confirmado' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ ucfirst($inscricao->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('alterar_status_inscricao') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_inscricao" value="{{ $inscricao->id }}">
                                                <button name="status" value="confirmado" class="btn btn-sm btn-success">Aprovar</button>
                                                <button name="status" value="cancelada" class="btn btn-sm btn-danger">Recusar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3">Ninguém se inscreveu ainda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection