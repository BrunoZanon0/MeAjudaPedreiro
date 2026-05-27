@extends('layouts.app')

@section('title', 'Página Inicial')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4">Bem-vindo ao Meajudaped</h1>
            <p class="lead">Sistema de gerenciamento de tarefas</p>
            <hr class="my-4">
            <p>Organize suas tarefas de forma simples e eficiente.</p>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Criar Nova Tarefa
            </a>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-list-check fs-1 text-primary"></i>
                    <h5 class="card-title mt-3">Organize</h5>
                    <p class="card-text">Gerencie suas tarefas de forma organizada</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-clock fs-1 text-success"></i>
                    <h5 class="card-title mt-3">Priorize</h5>
                    <p class="card-text">Defina prazos e prioridades</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-graph-up fs-1 text-info"></i>
                    <h5 class="card-title mt-3">Acompanhe</h5>
                    <p class="card-text">Veja seu progresso em tempo real</p>
                </div>
            </div>
        </div>
    </div>
@endsection