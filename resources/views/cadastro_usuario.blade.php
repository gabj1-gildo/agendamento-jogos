@extends('layouts.main_layout')
@section('content')
<!-- Registration Form -->

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-8">
            <div class="card p-5">
                <!-- logo -->
                <div class="text-center p-3">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Notas">
                </div>

                <!-- form -->
                <div class="row justify-content-center">
                    <div class="col-md-10 col-12">

                        <form method="POST" action="{{ route('salvarUsuario') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required>
                                @error('nome')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone') }}" required>
                                @error('telefone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                                @error('senha')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="senha_confirmation" class="form-label">Confirmar Senha</label>
                                <input type="password" class="form-control" id="senha_confirmation" name="senha_confirmation" required>
                                @error('senha_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                        </form>

                        @if ($errors->has('registerError'))
                            <div class="alert alert-danger text-center mt-3">
                                {{ $errors->first('registerError') }}
                            </div>
                        @endif

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}">Já tem conta? Entrar</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- End Registration Form -->
@endsection