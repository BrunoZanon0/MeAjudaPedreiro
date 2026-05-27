@extends('layouts.app')

@section('title', 'Cadastrar Trabalho Feito')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Cadastrar Novo Trabalho Feito</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('trabalhos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Título do Trabalho</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="images" class="form-label">Imagens (até 5)</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço</label>
                            <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="tempo_gasto" class="form-label">Tempo Gasto</label>
                            <input type="text" class="form-control" id="tempo_gasto" name="tempo_gasto">
                        </div>
                        <div class="mb-3">
                            <label for="localizacao" class="form-label">Localização</label>
                            <input type="text" class="form-control" id="localizacao" name="localizacao">
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                        <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">Voltar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection