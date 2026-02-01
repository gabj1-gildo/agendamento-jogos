<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(){
        return view('login');
    }

    public function loginSubmit(Request $request){
        // Lógica de autenticação aqui
        $request->validate(
            [
                'email' => 'required|email',
                'senha' => 'required|string|min:6|max:16',
            ],
            [
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'O campo e-mail deve ser um endereço de e-mail válido.',
                'senha.required' => 'O campo senha é obrigatório.',
                'senha.string' => 'O campo senha deve ser uma string.',
                'senha.min' => 'O campo senha deve ter no mínimo :min caracteres.',
                'senha.max' => 'O campo senha deve ter no máximo :max caracteres.',
            ]
        );

        $email = $request->input('email');
        $senha = $request->input('senha');

        $user = User::where('email', $email)
                    ->where('deleted_at', null)
                    ->first();

        if (!$user){
            return redirect()->back()->withErrors(['loginError' => 'Credenciais inválidas.'])->withInput();
        }

        if (!password_verify($senha, $user->senha)){
            return redirect()->back()->withErrors(['loginError' => 'Credenciais inválidas.'])->withInput();
        }

        // Autenticação bem-sucedida
        $user->ultimo_login = now();
        $user->save();

        // Criar sessão de usuário e tipo de usuário
        session(['user_id' => $user->id, 'user_nome' => $user->nome, 'user_tipo' => $user->tipo_usuario]);

        return redirect('/');
        // return redirect()->intended(route('home'));

    }

    public function logout(){
        // Destruir sessão de usuário
        session()->forget(['user_id', 'user_nome']);
        return redirect('/login');
    }

    public function cadastroUsuario() {
        return view('cadastro_usuario');
    }

    public function salvarUsuario(Request $request) {
        // Lógica para salvar o usuário
        $request->validate(
        [
            'nome' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'senha' => 'required|string|min:6',
        ], 
        [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'nome.min' => 'O campo nome deve ter no mínimo :min caracteres.',
            'nome.max' => 'O campo nome deve ter no máximo :max caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um endereço de email válido.',
            'email.unique' => 'Este email já está em uso.',
            'senha.required' => 'O campo senha é obrigatório.',
            'senha.string' => 'O campo senha deve ser uma string.',
            'senha.min' => 'O campo senha deve ter no mínimo :min caracteres.',
        ]);

        // Aqui você pode salvar o usuário no banco de dados
        $usuario = new User();
        $usuario->nome = $request->input('nome');
        $usuario->email = $request->input('email');
        $usuario->senha = bcrypt($request->input('senha'));
        $usuario->telefone = $request->input('telefone');
        $usuario->created_at = now();
        $usuario->save();

        return redirect('/login')->with('success', 'Usuário cadastrado com sucesso!');
    }
}