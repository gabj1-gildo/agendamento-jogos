@extends('layouts.main_layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            @include('top_bar')

            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0 text-center">Confirmar Inscrição</h4>
                </div>
                
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h5 class="text d-inline-block border-bottom border-2 pb-2 px-3">
                            {{ $jogo->titulo->nome ?? 'Jogo' }}
                        </h5>
                    </div>
                
                    <div class="row mb-3">
                        <div class="col-4">
                            <p class="mb-1"><strong>Data e Hora:</strong></p>
                            <p>{{ date('d/m/Y H:i', strtotime($jogo->data_hora)) }}</p>
                        </div>

                        <div class="col-4 text-center">
                            <p class="mb-1"><strong>Local:</strong></p>
                            <p>{{ $jogo->local->nome }}</p>
                        </div>

                        <div class="col-4 text-end">
                            <p class="mb-1"><strong>Vagas Restantes:</strong></p>
                            <span class="badge bg-info">
                                {{ $jogo->vagas - $jogo->inscricoes_count }} de {{ $jogo->vagas }}
                            </span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="text-center mb-4 p-3 bg-light rounded border">
                        <small class="text-secondary d-block mb-1 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Titular da Inscrição</small>
                        <span class="fw-bold fs-5 text-dark">{{ session('user_nome') }}</span>
                    </div>

                    <form action="{{ route('salvar_inscricao') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jogo_id" value="{{ $jogo->id }}">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                <i class="bi bi-check-circle me-2"></i>Confirmar Minha Vaga
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-link text-muted;">
                                Escolher outro vôlei
                            </a>
                        </div>
                    </form>
                </div>
                
            </div>

            @if(session('error'))
                <div class="alert alert-danger mt-3 text-center">
                    {{ session('error') }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection