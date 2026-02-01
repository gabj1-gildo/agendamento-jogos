@extends('layouts.main_layout')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col">
            @include('top_bar')

            @if(session('user_tipo') == 'admin' || session('user_tipo') == 'organizador')
                <div class="row mt-6">
                    <div class="col text-center">
                        {{-- @if (session('user_tipo') == 'admin')                 
                        <a href="{{ route('gerenciar_locais') }}" class="btn btn-primary">Gerenciar Locais</a>
                        <a href="{{ route('gerenciar_titulos') }}" class="btn btn-info">Gerenciar Titulos</a>
                        @endif --}}
                        {{-- <a href="{{ route('novo_jogo') }}" class="btn btn-info">Cadastrar Jogo</a> --}}
                        <a href="{{ route('gerenciar_inscricoes') }}" class="btn btn-info">Gerenciar Inscrições</a>
                        <a href="{{ route('gerenciar_jogos') }}" class="btn btn-success">Gerenciar Jogos</a>
                        
                    </div>


                </div>
                <hr>
            @endif

            @if(count($jogos) == 0)
                <div class="row mt-5">
                    <div class="col text-center">
                        <h3 class="text-secondary">Não há jogos disponíveis.</h3>
                    </div>
                </div>
                @else
                
                    @if(session('success'))
                        <div class="alert alert-success text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        @foreach($jogos as $jogo)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        
                                        <h3 class="card-title text-center">{{ $jogo->titulo->nome ?? 'Título não encontrado' }}</h3>
                                        
                                        <p class="card-text"><strong>Data do jogo:</strong> {{ date('d/m/Y H:i', strtotime($jogo['data_hora'])) }}</p>
                                        
                                        <p class="card-text"><strong>Inscrições até:</strong> {{ date('d/m/Y H:i', strtotime($jogo['data_hora_limite_inscricao'])) }}</p>   
                                        
                                        <p class="card-text flex-grow-1"><strong>Descrição:</strong> {{ Str::limit($jogo['descricao'], 100) }}</p>
                                        
                                        <p class="card-text"><strong>Local:</strong> {{ $jogo->local->nome ?? 'Local não encontrado' }}</p>
                                    
                                        <p class="card-text"><strong>Organizador:</strong> {{ $jogo->responsavel->nome ?? 'Organizador não encontrado' }}</p>
                                        {{-- <p class="card-text"><strong>Endereço:</strong> {{ $jogo->local->endereco ?? 'Endereço não encontrado' }}</p> --}}
                                        
                                        <p class="card-text">
                                            <strong>Vagas Disponíveis:</strong> 
                                            {{-- Calculando: Total menos o contador de inscritos --}}
                                            <span class="badge {{ ($jogo->vagas - $jogo->inscricoes_count) > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $jogo->vagas - $jogo->inscricoes_count }} de {{ $jogo->vagas }}
                                            </span>
                                        </p>

                                        <div class="mt-auto pt-3 border-top">
                                        <div class="d-flex gap-2">
                                            {{-- Botão Inscrever: Visível para todos, mas com lógica de login --}}
                                            <a href="{{ route('nova_inscricao', ['id' => Crypt::encrypt($jogo->id)]) }}" 
                                            class="btn btn-sm btn-success flex-grow-1">
                                                Inscrever
                                            </a>

                                            {{-- Botões Administrativos: Apenas para Admin
                                            @if(session('user_tipo') == 'admin')
                                                <a href="{{ route('editar_jogo', ['id' => Crypt::encrypt($jogo->id)]) }}" 
                                                class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i> Editar
                                                </a>
                                                
                                                <a href="{{ route('cancelar_jogo', ['id' => Crypt::encrypt($jogo->id)]) }}" 
                                                class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-circle"></i> Cancelar
                                                </a>
                                            @endif --}}
                                        </div>
                                    </div>

                                    </div>
                                    {{-- <div class="card-footer d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Última edição: {{ $jogo['updated_at'] }}</small>
                                        <a href="{{ route('editarPartida', ['id' => Crypt::encrypt($jogo['id']) ]) }}">Editar</a>
                                        <a href="{{ route('CancelarPartida', ['id' => Crypt::encrypt($jogo['id']) ]) }}" 
                                            class="text-danger">Deletar</a>
                                    </div> --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
            @endif
        </div>
    </div>
</div>
@endsection