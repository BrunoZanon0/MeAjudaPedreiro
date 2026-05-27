@extends('layouts.app')

@section('title', 'Trabalhos de ' . $pedreiro->name)

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-white">Trabalhos de {{ $pedreiro->name }}</h2>
            <a href="javascript:history.back()" class="btn btn-secondary">
                Página anterior
            </a>
        </div>
    </div>
    
    @if($trabalhos->count() > 0)
        <div class="row">
            @foreach($trabalhos as $trabalho)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-white">{{ $trabalho->title }}</h5>
                        <p class="card-text text-white">{{ $trabalho->description }}</p>
                        @if($trabalho->avaliacao)
                            <p class="mb-1 text-white"><strong>Avaliação:</strong> {{ $trabalho->avaliacao }}/5</p>
                        @endif
                        @if($trabalho->preco)
                            <p class="mb-1 text-white"><strong>Preço:</strong> R$ {{ number_format($trabalho->preco, 2, ',', '.') }}</p>
                        @endif
                        @if($trabalho->localizacao)
                            <p class="mb-1 text-white"><strong>Localização:</strong> {{ $trabalho->localizacao }}</p>
                        @endif
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
                                    $colClass = 'col-6';
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
                        <small class="text-white">Publicado em {{ $trabalho->created_at->format('d/m/Y') }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <h3>{{ $pedreiro->name }} ainda não cadastrou nenhum trabalho.</h3>
                <p>Volte mais tarde para ver os trabalhos realizados.</p>
            </div>
        </div>
    @endif
</div>
@endsection