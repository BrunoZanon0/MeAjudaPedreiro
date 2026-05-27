@extends('layouts.app')

@section('title', 'Detalhes do Trabalho')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            
            {{-- Card estilo Instagram --}}
            <div class="card instagram-card">
                
                {{-- Header com informações do usuário --}}
                <div class="card-header instagram-header">
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($trabalho->user->name) }}&background=0D6EFD&color=fff&size=40" 
                             class="rounded-circle me-2" 
                             style="width: 40px; height: 40px; object-fit: cover;">
                        <div>
                            <a href="{{ route('dashboard.perfilTag', str_replace('@', '', $trabalho->user->tag)) }}" 
                               class="text-white text-decoration-none fw-bold">
                                {{ $trabalho->user->name }}
                            </a>
                            <br>
                            <small class="text-white-50">{{ $trabalho->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>

                {{-- Carrossel de Imagens --}}
                @if($trabalho->images && count($trabalho->images) > 0)
                    <div id="carouselTrabalho" class="carousel slide" data-bs-ride="carousel">
                        
                        @if(count($trabalho->images) > 1)
                            <div class="carousel-indicators">
                                @foreach($trabalho->images as $index => $image)
                                    <button type="button" data-bs-target="#carouselTrabalho" 
                                            data-bs-slide-to="{{ $index }}" 
                                            class="{{ $index == 0 ? 'active' : '' }}"
                                            style="background-color: white;"></button>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="carousel-inner">
                            @foreach($trabalho->images as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         class="d-block w-100 instagram-image"
                                         style="cursor: pointer;"
                                         data-image="{{ asset('storage/' . $image) }}"
                                         data-index="{{ $index }}"
                                         onclick="openLightbox({{ $index }})"
                                         alt="Imagem do trabalho">
                                </div>
                            @endforeach
                        </div>
                        
                        @if(count($trabalho->images) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselTrabalho" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselTrabalho" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Próximo</span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="bg-dark text-white text-center p-5">
                        <i class="bi bi-image fs-1"></i>
                        <p>Nenhuma imagem disponível</p>
                    </div>
                @endif

                {{-- Conteúdo --}}
                <div class="card-body">

                    {{-- Descrição --}}
                    <div class="mb-2">
                        <span class="fw-bold text-white me-2">{{ $trabalho->user->name }}</span>
                        <span class="text-white">{{ $trabalho->description }}</span>
                    </div>

                    {{-- Detalhes adicionais --}}
                    @if($trabalho->avaliacao || $trabalho->preco || $trabalho->tempo_gasto || $trabalho->localizacao)
                        <div class="mt-3 pt-2 border-top border-secondary">
                            <div class="row g-2">
                                @if($trabalho->avaliacao)
                                    <div class="col-6">
                                        <small class="text-white-50 d-block">Avaliação</small>
                                        <div class="text-white">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $trabalho->avaliacao)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-1">({{ $trabalho->avaliacao }}/5)</span>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($trabalho->preco)
                                    <div class="col-6">
                                        <small class="text-white-50 d-block">Preço</small>
                                        <p class="text-white mb-0">R$ {{ number_format($trabalho->preco, 2, ',', '.') }}</p>
                                    </div>
                                @endif
                                
                                @if($trabalho->tempo_gasto)
                                    <div class="col-6">
                                        <small class="text-white-50 d-block">Tempo Gasto</small>
                                        <p class="text-white mb-0">{{ $trabalho->tempo_gasto }}</p>
                                    </div>
                                @endif
                                
                                @if($trabalho->localizacao)
                                    <div class="col-6">
                                        <small class="text-white-50 d-block">Localização</small>
                                        <p class="text-white mb-0"><i class="bi bi-geo-alt"></i> {{ $trabalho->localizacao }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Data de cadastro --}}
                    <small class="text-white-50 d-block mt-3">
                        <i class="bi bi-calendar3"></i> {{ $trabalho->created_at->format('d/m/Y \à\s H:i') }}
                    </small>
                </div>
            </div>

            {{-- Botão Voltar --}}
            <div class="text-center mt-4">
                <button onclick="window.history.back()" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left"></i> Voltar
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Lightbox (Imagem em Tela Cheia) --}}
<div id="lightbox" class="lightbox" onclick="closeLightbox()">
    <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
    <div class="lightbox-container">
        <img id="lightbox-img" class="lightbox-content">
        
        @if(count($trabalho->images) > 1)
            <button class="lightbox-prev" onclick="changeImage(-1)">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="lightbox-next" onclick="changeImage(1)">
                <i class="bi bi-chevron-right"></i>
            </button>
            <div class="lightbox-counter">
                <span id="current-image">1</span> / <span id="total-images">{{ count($trabalho->images) }}</span>
            </div>
        @endif
    </div>
</div>

<style>
    /* Estilo Instagram Card */
    .instagram-card {
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .instagram-header {
        background: transparent;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding: 12px 16px;
    }
    
    .instagram-image {
        max-height: 500px;
        width: 100%;
        object-fit: cover;
        cursor: pointer;
    }
    
    /* Carrossel personalizado */
    .carousel-indicators [data-bs-target] {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin: 0 4px;
    }
    
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0,0,0,0.5);
        border-radius: 50%;
        padding: 20px;
    }
    
    /* Lightbox (Tela Cheia) */
    .lightbox {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.95);
    }
    
    .lightbox-container {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .lightbox-content {
        display: block;
        max-width: 95%;
        max-height: 95%;
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 4px;
        box-shadow: 0 0 30px rgba(0,0,0,0.5);
    }
    
    .close-lightbox {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
        transition: 0.3s;
    }
    
    .close-lightbox:hover {
        color: #bbb;
    }
    
    .lightbox-prev,
    .lightbox-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        font-size: 30px;
        padding: 15px;
        cursor: pointer;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }
    
    .lightbox-prev {
        left: 20px;
    }
    
    .lightbox-next {
        right: 20px;
    }
    
    .lightbox-prev:hover,
    .lightbox-next:hover {
        background: rgba(0,0,0,0.8);
        transform: translateY(-50%) scale(1.1);
    }
    
    .lightbox-counter {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: bold;
    }
    
    /* Animação do lightbox */
    .lightbox-content {
        animation: zoomIn 0.3s ease;
    }
    
    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    @media (max-width: 768px) {
        .lightbox-prev,
        .lightbox-next {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }
        
        .close-lightbox {
            top: 10px;
            right: 15px;
            font-size: 30px;
        }
    }
</style>

<script>
    // Array com todas as imagens do trabalho
    const images = @json($trabalho->images);
    let currentImageIndex = 0;
    let lightboxImages = [];
    
    // Construir array com URLs completas das imagens
    images.forEach(function(image) {
        lightboxImages.push('{{ asset('storage/') }}/' + image);
    });
    
    // Abrir lightbox com a imagem específica
    function openLightbox(index) {
        currentImageIndex = index;
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        
        lightbox.style.display = 'block';
        lightboxImg.src = lightboxImages[currentImageIndex];
        
        // Atualizar contador
        updateLightboxCounter();
        
        // Prevenir scroll do body
        document.body.style.overflow = 'hidden';
    }
    
    // Fechar lightbox
    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Mudar imagem no lightbox
    function changeImage(direction) {
        currentImageIndex += direction;
        
        // Loop infinito
        if (currentImageIndex < 0) {
            currentImageIndex = lightboxImages.length - 1;
        } else if (currentImageIndex >= lightboxImages.length) {
            currentImageIndex = 0;
        }
        
        const lightboxImg = document.getElementById('lightbox-img');
        
        // Adicionar efeito de fade
        lightboxImg.style.opacity = '0';
        setTimeout(() => {
            lightboxImg.src = lightboxImages[currentImageIndex];
            lightboxImg.style.opacity = '1';
            updateLightboxCounter();
        }, 150);
    }
    
    // Atualizar contador de imagens no lightbox
    function updateLightboxCounter() {
        const counter = document.querySelector('.lightbox-counter');
        if (counter) {
            const currentSpan = document.getElementById('current-image');
            if (currentSpan) {
                currentSpan.textContent = currentImageIndex + 1;
            }
        }
    }
    
    // Fechar com tecla ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLightbox();
        }
        
        // Navegar com setas do teclado
        if (document.getElementById('lightbox').style.display === 'block') {
            if (event.key === 'ArrowLeft') {
                changeImage(-1);
            } else if (event.key === 'ArrowRight') {
                changeImage(1);
            }
        }
    });
    
    // Impedir que o clique no botão feche o lightbox
    document.querySelectorAll('.lightbox-prev, .lightbox-next').forEach(btn => {
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>

@endsection