@extends('layouts.app')

@section('title', 'Feed de Trabalhos')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        @foreach($trabalho as $item)
        <div class="col-md-8 mb-4">
            <div class="card">
                {{-- Cabeçalho com info do usuário --}}
                <div class="card-header d-flex align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $item->title }}</h5>
                        <small class="text-white-50">
                            por <a href="{{ route('dashboard.perfilTag', str_replace('@', '', $item->user->tag)) }}" class="text-white">
                                {{ $item->user->name ?? 'Usuário' }}
                            </a>
                        </small>
                    </div>
                </div>

                <div class="card-body">

                    {{-- Descrição --}}
                    <p class="text-white">{{ $item->description }}</p>

                    {{-- Detalhes em grid --}}
                    @if($item->avaliacao || $item->preco || $item->tempo_gasto || $item->localizacao)
                        <div class="row mb-3">
                            @if($item->avaliacao)
                                <div class="col-md-6">
                                    <small class="text-white-50">Avaliação:</small>
                                    <p class="text-white mb-0">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $item->avaliacao)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-warning"></i>
                                            @endif
                                        @endfor
                                        ({{ $item->avaliacao }}/5)
                                    </p>
                                </div>
                            @endif
                            
                            @if($item->preco)
                                <div class="col-md-6">
                                    <small class="text-white-50">Preço:</small>
                                    <p class="text-white mb-0">R$ {{ number_format($item->preco, 2, ',', '.') }}</p>
                                </div>
                            @endif
                            
                            @if($item->tempo_gasto)
                                <div class="col-md-6">
                                    <small class="text-white-50">Tempo Gasto:</small>
                                    <p class="text-white mb-0">{{ $item->tempo_gasto }}</p>
                                </div>
                            @endif
                            
                            @if($item->localizacao)
                                <div class="col-md-6">
                                    <small class="text-white-50">Localização:</small>
                                    <p class="text-white mb-0"><i class="bi bi-geo-alt"></i> {{ $item->localizacao }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- IMAGENS EM GRID ESTILO INSTAGRAM --}}
                    @if($item->images && count($item->images) > 0)
                        @php
                            $numImages = count($item->images);
                            
                            // Define o layout baseado no número de imagens
                            if ($numImages == 1) {
                                $gridClass = 'col-12';
                                $imageHeight = '400px';
                            } elseif ($numImages == 2) {
                                $gridClass = 'col-6';
                                $imageHeight = '250px';
                            } elseif ($numImages == 3) {
                                $gridClass = 'col-4';
                                $imageHeight = '200px';
                            } else {
                                $gridClass = 'col-6 col-md-4';
                                $imageHeight = '200px';
                            }
                        @endphp
                        
                        <div class="row g-2 mt-2">
                            @foreach($item->images as $index => $image)
                                <div class="{{ $gridClass }}">
                                    <a href="{{ route('dashboard.perfilTrabalho', ['tag' => str_replace('@', '', $item->user->tag), 'id' => $item->id]) }}">
                                        <div class="position-relative overflow-hidden rounded">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 class="img-fluid w-100"
                                                 style="height: {{ $imageHeight }}; object-fit: cover; transition: transform 0.3s;">
                                            @if($index == 3 && count($item->images) > 4)
                                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center">
                                                    <span class="text-white fs-3">+{{ count($item->images) - 3 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Footer com data --}}
                    <div class="mt-3 pt-2 border-top border-secondary">
                        <small class="text-white-50">
                            <i class="bi bi-clock"></i> {{ $item->created_at->format('d/m/Y \à\s H:i') }}
                        </small>
                    </div>

                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    
    img {
        transition: transform 0.3s ease;
    }
    
    img:hover {
        transform: scale(1.05);
    }
</style>
@endsection