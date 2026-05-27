@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card w-100" style="max-width: 400px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-white">Bem-vindo de volta</h2>
                <p class="text-white">Entre na sua conta</p>
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

            <!-- Botão Google -->
            <div class="d-grid gap-2 mb-3">
                <a href="{{ route('google.redirect') }}" class="btn btn-outline-light">
                    <i class="bi bi-google me-2"></i>Continuar com Google
                </a>
            </div>

            <div class="text-center mb-3">
                <span class="text-white">ou</span>
            </div>

            <!-- Formulário normal -->
            <form action="{{ route('login.authenticate') }}" method="POST">
                @csrf
                
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
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="••••••••"
                           required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Lembrar-me</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <a href="#" class="text-decoration-none">Esqueceu a senha?</a>
            </div>

            <div class="text-center mt-3">
                <span class="text-white">Não tem conta? </span>
                <a href="{{ route('register.form') }}" class="text-decoration-none fw-bold">Cadastre-se</a>
            </div>
        </div>
    </div>
</div>
@endsection