@extends('layouts.main_layout')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col">

            @include('top_bar')
            {{-- data do jogo
            hora do jogo
            local do jogo
            responsavel pelo jogo
            limite de jogadores
            descricao do jogo
            data limite para inscricao
            hora limite para inscricao
            custo do jogo
            status do jogo (aberto, fechado, cancelado)

             --}}
            <div class="card p-5">
                <h2 class="mb-4 text-center">Cadastrar jogo</h2>
                <form method="POST" action="{{ route('salvar_jogo') }}">
                    @csrf


                    <div class="mb-3">
                        <label for="responsavel" class="form-label">Organizador</label>
                        <input type="text" class="form-control" id="responsavel" name="responsavel" value="{{ session('user_nome') }}" readonly>
                        <input type="hidden" name="responsavel_id" value="{{ session('user_id') }}">
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título do jogo</label>
                        <select class="form-control" id="titulo" name="titulo" required>
                            <option value="" disabled selected>Selecione o título</option>
                            @foreach ($titulos as $titulo)
                                <option value="{{ $titulo->id }}" {{ old('titulo') == $titulo->id ? 'selected' : '' }}>
                                    {{ $titulo->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('titulo')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="data" class="form-label">Data do jogo</label>
                        <input type="date" class="form-control" id="data" name="data" value="{{ old('data') }}" required>
                        @error('data')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="hora" class="form-label">Hora do jogo</label>
                        <input type="time" class="form-control" id="hora" name="hora" value="{{ old('hora') }}" required>
                        @error('hora')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                        <label for="local" class="form-label">Local do jogo</label>
                        <select class="form-control" id="local" name="local" required>
                            <option value="" disabled selected>Selecione o local</option>
                            @foreach ($locais as $local)
                                <option value="{{ $local->id }}" {{ old('local') == $local->id ? 'selected' : '' }}>
                                    {{ $local->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('local')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label for="limite_jogadores" class="form-label">Limite de jogadores</label>
                        <input type="number" class="form-control" id="limite_jogadores" name="limite_jogadores" value="{{ old('limite_jogadores') }}" required>
                        @error('limite_jogadores')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição do jogo</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="5" required>{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="data_limite_inscricao" class="form-label">Data limite para inscrição</label>
                        <input type="date" class="form-control" id="data_limite_inscricao" name="data_limite_inscricao" value="{{ old('data_limite_inscricao') }}" required>
                        @error('data_limite_inscricao')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="hora_limite_inscricao" class="form-label">Hora limite para inscrição</label>
                        <input type="time" class="form-control" id="hora_limite_inscricao" name="hora_limite_inscricao" value="{{ old('hora_limite_inscricao') }}" required>
                        @error('hora_limite_inscricao')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status do jogo</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="aberto" {{ old('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                            {{-- <option value="fechado" {{ old('status') == 'fechado' ? 'selected' : '' }}>Fechado</option> --}}
                            {{-- <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option> --}}
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100">Salvar Jogo</button>
                    
                    @if (session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                </form>
            </div>

        </div>
    </div>

@endsection