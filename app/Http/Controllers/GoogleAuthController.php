<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\UserSessionService;

class GoogleAuthController extends Controller
{
    // Redirecionar para o Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback do Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Verificar se usuário já existe
            $user = User::where('email', $googleUser->getEmail())->first();

            
            if (!$user) {
                // Criar novo usuário
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'tag' => '@' . strtolower(str_replace(' ', '_', $googleUser->getName())),
                    'password' => Hash::make(uniqid()), // Senha aleatória
                    'profession' => null, // Vai pedir depois
                ]);
            } else {
                // Atualizar google_id se não tiver
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            }
            
            // Logar o usuário
            Auth::login($user);
            session()->regenerate();

            // Cachear dados do usuário no Redis
            UserSessionService::cacheUserData($user);
            
            // Se não tiver profissão, redirecionar para escolher
            if (!$user->profession) {
                return redirect()->route('choose.profession')
                    ->with('warning', 'Por favor, escolha sua profissão para continuar.');
            }

            // Se não tiver CPF ou CNPJ preenchido, redirecionar para o formulário obrigatório
            if (!$user->cpf && !$user->cnpj) {
                return redirect()->route('cpf.cnpj.form')
                    ->with('warning', 'Por favor, preencha seu CPF ou CNPJ para continuar.');
            }
            
            return redirect()->intended('/dashboard')
                ->with('success', 'Bem-vindo, ' . $user->name . '!');
                
        } catch (\Exception $e) {
            return redirect('/login')
                ->with('error', 'Erro ao autenticar com Google: ' . $e->getMessage());
        }
    }
}