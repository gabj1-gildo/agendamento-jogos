@extends('layouts.main_layout')

@section('content')
<div class="container mt-5">
    @include('top_bar')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-geo-alt-fill text-primary me-2"></i>Gerenciamento de Locais</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoLocal">
            <i class="bi bi-plus-lg"></i> Novo Local
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-4">Nome</th>
                    <th>Endereço</th>
                    <th class="text-center">Acesso</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locais as $local)
                <tr>
                    <td class="ps-4"><strong>{{ $local->nome }}</strong></td>
                    <td>{{ $local->endereco ?? 'Não informado' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $local->tipo === 'publico' ? 'bg-info text-dark' : 'bg-secondary' }}">
                            {{ ucfirst($local->tipo) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning" 
                                onclick="abrirModalTipo('{{ $local->id }}', '{{ $local->tipo }}')">
                            <i class="bi bi-pencil-square"></i> Alterar Tipo
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalNovoLocal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('salvar_local') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Cadastrar Novo Local</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nome do Local</label>
                    <input type="text" name="nome" class="form-control" placeholder="Ex: Quadra do Centro" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Endereço</label>
                    <input type="text" name="endereco" class="form-control" placeholder="Rua, Número, Bairro">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tipo</label>
                    <select name="tipo" class="form-select" required>
                        <option value="publico">Público</option>
                        <option value="privado">Privado</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar Local</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditarTipo" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('atualizar_local') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="id" id="edit_local_id">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Alterar Acesso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <select name="tipo" id="edit_local_tipo" class="form-select">
                    <option value="publico">Público</option>
                    <option value="privado">Privado</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning w-100">Atualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalTipo(id, tipo) {
        document.getElementById('edit_local_id').value = id;
        document.getElementById('edit_local_tipo').value = tipo;
        new bootstrap.Modal(document.getElementById('modalEditarTipo')).show();
    }
</script>
@endsection