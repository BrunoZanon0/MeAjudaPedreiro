@extends('layouts.app')

@section('title', 'Criar Conta')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card w-100" style="max-width: 400px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Criar Conta</h2>
                <p class="text-white">Junte-se ao Meajudaped</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nome Completo</label>
                    <input type="text" 
                           class="form-control" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="Seu nome completo"
                           required 
                           autofocus>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="seu@email.com"
                           required>
                </div>

                <div class="mb-3">
                    <label for="CPF" class="form-label">CPF</label>
                    <input type="text" 
                           class="form-control" 
                           id="CPF" 
                           name="CPF" 
                           value="{{ old('CPF') }}" 
                           placeholder="000.000.000-00">
                </div>

                <div class="mb-3">
                    <label for="CNPJ" class="form-label">CNPJ</label>
                    <input type="text" 
                           class="form-control" 
                           id="CNPJ" 
                           name="CNPJ" 
                           value="{{ old('CNPJ') }}" 
                           placeholder="00.000.000/0000-00">
                    <div class="form-text">Informe CPF ou CNPJ. Pelo menos um dos dois deve ser preenchido.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profissão</label>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" 
                                       name="profession" value="consultor" 
                                       id="consultor" 
                                       {{ old('profession') == 'consultor' ? 'checked' : '' }}
                                       required>
                                <label class="form-check-label" for="consultor">
                                    <i class="bi bi-chat-dots me-1"></i>Consultor
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" 
                                       name="profession" value="pedreiro" 
                                       id="pedreiro"
                                       {{ old('profession') == 'pedreiro' ? 'checked' : '' }}
                                       required>
                                <label class="form-check-label" for="pedreiro">
                                    <i class="bi bi-tools me-1"></i>Pedreiro
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="••••••••"
                           required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="••••••••"
                           required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Criar Conta</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <span class="text-white">Já tem conta? </span>
                <a href="{{ route('login.form') }}" class="text-decoration-none fw-bold">Entrar</a>
            </div>
        </div>
    </div>
</div>
@endsection