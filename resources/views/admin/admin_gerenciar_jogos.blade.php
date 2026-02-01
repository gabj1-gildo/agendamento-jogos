@extends('layouts.main_layout')

@section('content')
    <div class="container mt-5">
        @include('top_bar')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gerenciamento de Jogos</h2>
            @if (session('user_tipo') == 'admin' || session('user_tipo') == 'organizador')
                <div class="btn btn-group">
                    @if (session('user_tipo') == 'admin')
                        <a href="{{ route('gerenciar_locais') }}" class="btn btn-info">Gerenciar Locais</a>
                        <a href="{{ route('gerenciar_titulos') }}" class="btn btn-warning">Gerenciar Titulos</a>
                    @endif
                    <a href="{{ route('novo_jogo') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Novo Jogo</a>
                </div>  
            @endif             
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="accordion shadow-sm" id="accordionOrganizadores">
            @forelse($jogosAgrupados as $organizador => $jogos)
                <div class="accordion-item mb-3 border-0 shadow-sm">
                    <h2 class="accordion-header" id="heading{{ Str::slug($organizador) }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($organizador) }}">
                            <div class="d-flex justify-content-between w-100 me-3 align-items-center">
                                <span>
                                    <i class="bi bi-person-badge me-2 text-primary"></i>
                                    <strong>Organizador:</strong> {{ $organizador }}
                                </span>
                                <span class="badge bg-dark rounded-pill">
                                    {{ $jogos->count() }} jogo(s) cadastrado(s)
                                </span>
                            </div>
                        </button>
                    </h2>
                    
                    <div id="collapse{{ Str::slug($organizador) }}" class="accordion-collapse collapse" data-bs-parent="#accordionOrganizadores">
                        <div class="accordion-body bg-white">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Título</th>
                                            <th>Data/Hora</th>
                                            <th>Local</th>
                                            <th class="text-center">Vagas</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jogos as $jogo)
                                            <tr>
                                                <td><strong>{{ $jogo->titulo->nome }}</strong></td>
                                                <td>{{ date('d/m H:i', strtotime($jogo->data_hora)) }}</td>
                                                <td>{{ $jogo->local->nome }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-info text-dark">
                                                        {{ $jogo->inscricoes_count }} / {{ $jogo->vagas }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $jogo->status === 'aberto' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ ucfirst($jogo->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{-- Verificação de prazo expirado --}}
                                                    @if($jogo->status === 'aberto' && $jogo->data_hora_limite_inscricao <= now())
                                                        <br><small class="text-danger fw-bold">Prazo Expirado</small>
                                                    @endif
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('editar_jogo', ['id' => Crypt::encrypt($jogo->id)]) }}" 
                                                        class="btn btn-warning" title="Editar">
                                                            <i class="bi bi-pencil">Editar</i>
                                                        </a>
                                                        
                                                        <a href="{{ route('gerenciar_inscricoes') }}?jogo={{ $jogo->id }}" 
                                                        class="btn btn-success" title="Ver Inscritos">
                                                            <i class="bi bi-people">Ver Inscritos</i>
                                                        </a>

                                                        @if($jogo->status === 'aberto')
                                                            {{-- Botão para ENCERRAR --}}
                                                            <button type="button" class="btn btn-danger" 
                                                                    onclick="confirmarAcao('{{ Crypt::encrypt($jogo->id) }}', 'encerrar')" 
                                                                    title="Encerrar Jogo">
                                                                <i class="bi bi-slash-circle">Encerrar</i>
                                                            </button>                                        
                                                        @else
                                                            {{-- Botão para REABRIR --}}
                                                            <button type="button" class="btn btn-info" 
                                                                    onclick="confirmarAcao('{{ Crypt::encrypt($jogo->id) }}', 'abrir')" 
                                                                    title="Abrir Jogo">
                                                                <i class="bi bi-check-circle">Reabrir</i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">Nenhum jogo cadastrado no momento.</div>
            @endforelse
        </div>
    </div>

    {{-- Script simples para confirmação de exclusão/cancelamento --}}
    <script>
        function confirmarAcao(id, acao) {
            let mensagem = acao === 'encerrar' 
                ? 'Tem certeza que deseja ENCERRAR este jogo? Ele não aparecerá mais para inscrições.' 
                : 'Deseja REABRIR este jogo para novas inscrições?';

            if(confirm(mensagem)) {
                window.location.href = "{{ route('cancelar_jogo') }}?id=" + id;
            }
        }
    </script>
@endsection