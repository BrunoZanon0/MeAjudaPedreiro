{{-- resources/views/dashboard/editar-perfil.blade.php --}}

@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card">
                <div class="card-header">
                    <h4>Editar Perfil</h4>
                </div>

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $erro)
                                <div>{{ $erro }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('perfil.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Tag Perfil</label>
                            <input
                                type="text"
                                name="tag"
                                class="form-control"
                                value="{{ old('tag', $user->tag) }}"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CPF</label>
                            <input
                                type="text"
                                name="cpf"
                                class="form-control"
                                value="{{ old('CPF', $user->CPF) }}"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label">CNPJ</label>
                            <input
                                type="text"
                                name="cnpj"
                                class="form-control"
                                value="{{ old('CNPJ', $user->CNPJ) }}"
                                required
                            >
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Nova Senha</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar Nova Senha</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                            >
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary">
                                Salvar Alterações
                            </button>

                            <a href="{{ route('dashboard.perfil') }}"
                               class="btn btn-secondary">
                                Cancelar
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection