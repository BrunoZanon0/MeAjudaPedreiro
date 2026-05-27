{{-- resources/views/dashboard/perfil.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        {{-- FOTO E BOTÃO --}}
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D6EFD&color=fff&size=220"
                                 class="rounded-circle img-fluid"
                                 style="width:180px;height:180px;object-fit:cover;border:3px solid #0D6EFD;">
                            
                            @if(Auth::id() !== $user->id)
                                <div class="mt-3">
                                    <button class="btn {{ $isFollowing ? 'btn-success following' : 'btn-primary' }} follow-btn w-100"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            style="border-radius: 12px; font-weight: 600;">
                                        <i class="bi {{ $isFollowing ? 'bi-person-check-fill' : 'bi-person-plus-fill' }}"></i>
                                        <span class="follow-text">{{ $isFollowing ? 'Seguindo' : 'Seguir' }}</span>
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- INFO --}}
                        <div class="col-md-9">
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                <h2 class="mb-0 text-white me-3">{{ $user->name }}</h2>
                                @if(Auth::id() === $user->id)
                                    <a href="{{ route('perfil.edit') }}" class="btn btn-outline-light btn-sm">
                                        Editar perfil
                                    </a>
                                @endif
                            </div>

                            <div class="d-flex gap-4 mb-3 text-white flex-wrap">
                                <span><strong>{{ $trabalhos->count() }}</strong> publicações</span>
                                <span>
                                    <strong class="followers-count">{{ $totalSeguidores }}</strong> 
                                    seguidores
                                </span>
                                <span><strong>{{ $totalSeguindo }}</strong> seguindo</span>
                            </div>

                            <div class="text-white">
                                <strong>{{ ucfirst($user->profession ?? 'Usuário') }}</strong><br>
                                @if($user->cidade ?? false)
                                    <i class="bi bi-geo-alt-fill"></i> {{ $user->cidade }}<br>
                                @endif
                                {{ $user->bio ?? 'Perfil profissional na plataforma.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRID POSTS --}}
            <div class="row">
                @forelse($trabalhos as $item)
                    <div class="col-md-4 col-6 mb-4">
                        <a href="{{ route('dashboard.perfilTrabalho', ['tag' => str_replace('@', '', $item->user->tag), 'id' => $item->id]) }}"
                           class="text-decoration-none">
                            <div class="card border-0 overflow-hidden shadow-sm hover-card">
                                @if(!empty($item->images[0]))
                                    <img src="{{ asset('storage/' . $item->images[0]) }}"
                                         class="w-100"
                                         style="height:260px;object-fit:cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-dark text-white"
                                         style="height:260px;">
                                        <i class="bi bi-image fs-1"></i>
                                    </div>
                                @endif
                                <div class="card-body p-2">
                                    <small class="text-white">{{ \Illuminate\Support\Str::limit($item->title, 35) }}</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center text-white mt-5">
                        <i class="bi bi-camera fs-1"></i>
                        <h4 class="mt-3">Nenhuma publicação ainda</h4>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const followBtn = document.querySelector('.follow-btn');
    
    if (followBtn) {
        const userId = followBtn.dataset.userId;
        const userName = followBtn.dataset.userName;
        
        followBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isCurrentlyFollowing = this.classList.contains('following');
            
            if (!isCurrentlyFollowing) {
                // Confirmar SEGUIR
                Swal.fire({
                    title: `Seguir ${userName}?`,
                    text: "Você verá as atualizações no seu feed.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0095f6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Seguir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/follow/${userId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Atualizar botão
                                followBtn.classList.add('following');
                                followBtn.classList.remove('btn-primary');
                                followBtn.classList.add('btn-success');
                                followBtn.querySelector('i').className = 'bi bi-person-check-fill';
                                followBtn.querySelector('.follow-text').textContent = 'Seguindo';
                                
                                // Atualizar contador
                                document.querySelector('.followers-count').textContent = data.followers_count;
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Seguindo!',
                                    text: `Você agora está seguindo ${userName}.`,
                                    timer: 2000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }
                        });
                    }
                });
            } else {
                // Confirmar DESSeguir
                Swal.fire({
                    title: `Deixar de seguir ${userName}?`,
                    text: "Você não receberá mais as atualizações.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#0095f6',
                    confirmButtonText: 'Deixar de seguir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Chamar API para desseguir
                        fetch(`/follow/${userId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Atualizar botão
                                followBtn.classList.remove('following');
                                followBtn.classList.remove('btn-success');
                                followBtn.classList.add('btn-primary');
                                followBtn.querySelector('i').className = 'bi bi-person-plus-fill';
                                followBtn.querySelector('.follow-text').textContent = 'Seguir';
                                
                                // Atualizar contador
                                document.querySelector('.followers-count').textContent = data.followers_count;
                                
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Deixou de seguir',
                                    text: `Você deixou de seguir ${userName}.`,
                                    timer: 2000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }
                        });
                    }
                });
            }
        });
    }
});
</script>

<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .follow-btn {
        transition: all 0.3s ease;
    }
    .follow-btn:active {
        transform: scale(0.97);
    }
</style>
@endsection