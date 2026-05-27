<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Meajudapedreiro')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Sweet Alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding-bottom: 80px; /* Espaço para nav inferior */
        }
        
        /* Cards modernos */
        .card {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.4);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-bottom: none;
            border-radius: 16px 16px 0 0 !important;
            padding: 1rem;
        }
        
        .card-body {
            background: transparent;
            padding: 1.5rem;
        }
        
        /* Formulários modernos */
        .form-control {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            color: #ffffff;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            background: rgba(255,255,255,0.15);
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            color: #ffffff;
        }
        
        .form-control::placeholder {
            color: rgba(255,255,255,0.6);
        }
        
        .form-label {
            color: #ffffff;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
        }
        
        .btn-info {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .btn-info:hover {
            background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
        }
        
        /* Navbar superior moderna */
        .navbar {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%) !important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            padding: 0.5rem 1rem;
        }
        
        .navbar-brand {
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: color 0.3s;
        }
        
        .navbar-nav .nav-link:hover {
            color: #667eea !important;
        }
        
        /* Menu inferior mobile */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
            border-top: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 -2px 10px rgba(0,0,0,0.2);
            z-index: 1030;
            display: flex;
            justify-content: space-around;
            padding: 0.5rem 0;
        }
        
        .mobile-nav a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            text-align: center;
            padding: 0.5rem;
            border-radius: 12px;
            transition: all 0.3s;
            flex: 1;
        }
        
        .mobile-nav a:hover, .mobile-nav a.active {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }
        
        .mobile-nav i {
            font-size: 1.5rem;
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .mobile-nav span {
            font-size: 0.75rem;
        }
        
        /* Esconder mobile-nav em desktop */
        @media (min-width: 768px) {
            .mobile-nav {
                display: none;
            }
            body {
                padding-bottom: 0;
            }
        }
        
        /* Container responsivo */
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Imagens responsivas */
        .img-fluid {
            border-radius: 12px;
        }
        
        /* Alertas modernos */
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: #ffffff;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: #ffffff;
        }
        
        /* Scrollbar moderna */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #0f0f0f;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        
        /* Animações suaves */
        * {
            transition: background-color 0.3s, border-color 0.3s, color 0.3s;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/dashboard') }}">
                <i class="bi bi-box-seam"></i> 
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="/dashboard">
                                        <i class="bi bi-speedometer2"></i> Dashboard
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Registrar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')

    <!-- Menu Inferior Mobile -->
    <nav class="mobile-nav d-md-none">
        <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i>
            <span>Início</span>
        </a>
        @auth
            @if(auth()->user()->profession === 'pedreiro')
                <a href="{{ route('trabalhos.create') }}" class="{{ request()->routeIs('trabalhos.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Adicionar</span>
                </a>
            @else 
                <a href="{{ route('feed.index') }}" class="{{ request()->routeIs('feed.index') ? 'active' : '' }}">
                    <i class="bi bi-bricks"></i>
                    <span>Obras</span>
                </a>
            @endif
        @endauth
        <a href="{{ route('dashboard.perfil') }}" class="{{ request()->routeIs('dashboard.perfil') ? 'active' : '' }}" >
            <i class="bi bi-person"></i>
            <span>Perfil</span>
        </a>
    </nav>
</body>
</html>