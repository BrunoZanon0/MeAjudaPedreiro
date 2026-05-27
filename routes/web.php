<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrabalhoFeitoController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
*/
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::get('/login', 'login')->name('login.form');
    Route::post('/login', 'authenticate')->name('login.authenticate');
    Route::get('/register', 'showRegisterForm')->name('register.form');
    Route::post('/register', 'register')->name('register');
    Route::post('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| Autenticação com Google
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->controller(GoogleAuthController::class)->group(function () {
    Route::get('/google', 'redirect')->name('google.redirect');
    Route::get('/google/callback', 'callback')->name('google.callback');
});

/*
|--------------------------------------------------------------------------
| Rotas de Profissão e CPF/CNPJ (Usuário autenticado)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/choose-profession', fn() => view('auth.choose-profession'))->name('choose.profession');
    Route::post('/choose-profession', [AuthController::class, 'updateProfession'])->name('update.profession');
    
    Route::controller(AuthController::class)->group(function () {
        Route::get('/cpf-cnpj', 'showCpfCnpjForm')->name('cpf.cnpj.form');
        Route::post('/cpf-cnpj', 'updateCpfCnpj')->name('cpf.cnpj.submit');
    });
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Requer Autenticação)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Perfil do usuário
    Route::prefix('/perfil')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'showPerfil')->name('dashboard.perfil');
        Route::get('/editar', 'editPerfil')->name('perfil.edit');
        Route::put('/editar', 'updatePerfil')->name('perfil.update');
        Route::get('/{tag}', 'showPerfilWithTag')->name('dashboard.perfilTag');
        Route::get('/{tag}/trabalho/{id}', 'showTrabalhoFromPerfil')->name('dashboard.perfilTrabalho');
    });
    
    // Feed
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
    
    // Trabalhos (CRUD completo)
    Route::resource('trabalhos', TrabalhoFeitoController::class);
    
    Route::prefix('follow')->controller(FollowController::class)->group(function () {
        Route::post('/{user}', 'toggleFollow')->name('follow.toggle');
        
        Route::post('/{user}/follow', 'follow')->name('follow.follow');
        Route::delete('/{user}/unfollow', 'unfollow')->name('follow.unfollow');
        
        Route::get('/{user}/check', 'checkFollow')->name('follow.check');
    });
    
    // Listar seguidores e seguindo (rotas mais simples)
    Route::get('/users/{user}/followers', [FollowController::class, 'followers'])->name('users.followers');
    Route::get('/users/{user}/following', [FollowController::class, 'following'])->name('users.following');
});