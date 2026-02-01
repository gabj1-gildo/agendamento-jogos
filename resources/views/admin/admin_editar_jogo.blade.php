@extends('layouts.main_layout')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                @include('top_bar')

                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Vôlei</h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="{{ route('atualizar_jogo') }}" method="POST">
                            @csrf
                            
                            {{-- ID criptografado para segurança no processamento --}}
                            <input type="hidden" name="id" value="{{ Crypt::encrypt($jogo->id) }}">

                            <div class="row">
                                {{-- Título do Jogo --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Título do Evento</label>
                                    <select name="titulo_id" class="form-select" required>
                                        @foreach($titulos as $titulo)
                                            <option value="{{ $titulo->id }}" {{ $jogo->titulo_id == $titulo->id ? 'selected' : '' }}>
                                                {{ $titulo->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Local do Jogo --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Local</label>
                                    <select name="local_id" class="form-select" required>
                                        @foreach($locais as $local)
                                            <option value="{{ $local->id }}" {{ $jogo->local_id == $local->id ? 'selected' : '' }}>
                                                {{ $local->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Data e Hora --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Data e Hora</label>
                                    {{-- O formato Y-m-d\TH:i é essencial para o input datetime-local carregar o valor --}}
                                    <input type="datetime-local" name="data_hora" class="form-control" 
                                        value="{{ date('Y-m-d\TH:i', strtotime($jogo->data_hora)) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Data e Hora Limite de Inscrição</label>
                                    <input type="datetime-local" name="data_hora_limite_inscricao" class="form-control" 
                                        value="{{ date('Y-m-d\TH:i', strtotime($jogo->data_hora_limite_inscricao)) }}" required>
                                </div>

                                {{-- Vagas --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Vagas Totais</label>
                                    <input type="number" name="vagas" class="form-control" value="{{ $jogo->vagas }}" min="1" required>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="aberto" {{ $jogo->status == 'aberto' ? 'selected' : '' }}>Aberto</option>
                                        <option value="encerrado" {{ $jogo->status == 'encerrado' ? 'selected' : '' }}>Encerrado</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Descrição --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Descrição / Observações</label>
                                <textarea name="descricao" class="form-control" rows="3">{{ $jogo->descricao }}</textarea>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('gerenciar_jogos') }}" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Voltar
                                </a>
                                <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                    <i class="bi bi-save me-2"></i>Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection