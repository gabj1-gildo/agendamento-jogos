@extends('layouts.main_layout')
@section('content')
<!-- Login Form -->

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-8">
            <div class="card p-5">
                <!-- logo -->
                <div class="text-center p-3">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
                </div>

                <!-- form -->
                <div class="row justify-content-center">
                    <div class="col-md-10 col-12">

                        <form method="POST" action="{{ route('loginSubmit') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
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
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        @if ($errors->has('loginError'))
                            <div class="alert alert-danger text-center">
                                {{ $errors->first('loginError') }}
                            </div>
                        @endif
                        <div class="text-center mt-3">
                            <a href="{{ route('cadastroUsuario') }}">Não tem uma conta? Cadastre-se</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- End Login Form -->
@endsection
