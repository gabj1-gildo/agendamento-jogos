@extends('layouts.main_layout')

@section('content')
    <div class="container mt-5">
        @include('top_bar')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-bookmark-star-fill text-warning me-2"></i>Gerenciar Títulos</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoTitulo">
                <i class="bi bi-plus-lg"></i> Novo Título
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0 col-md-8">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">Nome do Título</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($titulos as $titulo)
                    <tr>
                        <td class="ps-4"><strong>{{ $titulo->nome }}</strong></td>
                        <td class="text-center">
                            {{-- Trava visual: só mostra o botão para Admin --}}
                            @if(session('user_tipo') === 'admin')
                                <button class="btn btn-sm btn-warning" 
                                        onclick="abrirModalEditar('{{ $titulo->id }}', '{{ $titulo->nome }}')">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>
                            @else
                                <span class="text-muted small"><i class="bi bi-lock"></i> Bloqueado</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalNovoTitulo" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('salvar_titulo') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Cadastrar Título</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-bold">Nome do Título</label>
                    <input type="text" name="nome" class="form-control" placeholder="Ex: Vôlei Misto Iniciante" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditarTitulo" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('atualizar_titulo') }}" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="id" id="edit_titulo_id">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Editar Título</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-bold">Nome do Título</label>
                    <input type="text" name="nome" id="edit_titulo_nome" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning w-100">Atualizar Título</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalEditar(id, nome) {
            document.getElementById('edit_titulo_id').value = id;
            document.getElementById('edit_titulo_nome').value = nome;
            new bootstrap.Modal(document.getElementById('modalEditarTitulo')).show();
        }
    </script>
@endsection