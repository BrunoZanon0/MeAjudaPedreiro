@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    @if(auth()->user()->profession === 'pedreiro')
        @if($trabalhos->count() > 0)
        <div class="row">
            <h2>Meus Trabalhos Feitos</h2>
            @foreach($trabalhos as $trabalho)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-white">{{ $trabalho->title }}</h5>
                        <p class="card-text text-white">{{ $trabalho->description }}</p>
                        @if($trabalho->images)
                            @php
                                $numImages = count($trabalho->images);
                                if ($numImages == 1) {
                                    $colClass = 'col-12';
                                } elseif ($numImages == 2) {
                                    $colClass = 'col-6';
                                } elseif ($numImages == 3) {
                                    $colClass = 'col-4';
                                } else {
                                    $colClass = 'col-6'; // para 4 ou mais
                                }
                            @endphp
                            <div class="row">
                                @foreach($trabalho->images as $image)
                                    <div class="{{ $colClass }} mb-2">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Imagem do trabalho" class="img-fluid w-100" style="height: 150px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <a href="{{ route('trabalhos.show', $trabalho->id) }}" class="btn btn-info">Ver Detalhes</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h3>Você ainda não cadastrou nenhum trabalho feito.</h3>
                <p>Comece compartilhando seus trabalhos para mostrar sua experiência!</p>
                <a href="{{ route('trabalhos.create') }}" class="btn btn-primary">Cadastrar Trabalho Feito</a>
            </div>
        </div>
        @endif
    @else
        <!-- Para consultores: barra de pesquisa -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center mb-4 text-white">Buscar Profissionais</h3>
                        <form action="{{ route('dashboard.index') }}" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="query" placeholder="Digite o nome do pedreiro..." value="{{ request('query') }}" required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> Buscar
                                </button>
                            </div>
                        </form>
                        @if(request('query'))
                            <h5 class="text-white">Resultados para "{{ request('query') }}":</h5>
                            @if($pedreiros->count() > 0)
                                <div class="list-group">
                                    @foreach($pedreiros as $pedreiro)
                                        <a href="{{ route('dashboard.perfilTag', str_replace('@', '', $pedreiro->tag)) }}" class="list-group-item list-group-item-action">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $pedreiro->name }}</h6>
                                                    <small class="text-white">{{ $pedreiro->trabalhosFeitos->count() }} trabalhos realizados</small>
                                                </div>
                                                <i class="bi bi-chevron-right"></i>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-white">Nenhum pedreiro encontrado com esse nome.</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection