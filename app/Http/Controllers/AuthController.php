<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Interfaces\AuthControllerInterface;
use App\Http\Repository\AuthRepository;

class AuthController extends Controller implements AuthControllerInterface
{
    protected $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mostrar formulário de login
     */
    public function login(): View
    {
        return view('login.login');
    }

    /**
     * Processar o login (POST)
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->repository->authenticate($credentials, $request->remember);

        if ($result['success']) {
            $request->session()->regenerate();
            
            $redirect = $this->repository->getLoginRedirect($result['user']);
            return redirect()->intended($redirect)->with('success', $result['message']);
        }

        return back()->withErrors([
            'email' => $result['message'],
        ])->onlyInput('email');
    }

    /**
     * Mostrar formulário de registro
     */
    public function showRegisterForm(): View
    {
        return view('login.register');
    }

    /**
     * Mostrar formulário de CPF/CNPJ
     */
    public function showCpfCnpjForm(): View|RedirectResponse
    {
        if ($this->repository->hasCpfOrCnpj()) {
            return redirect()->route('dashboard.index');
        }
        
        return view('auth.cpf-cnpj');
    }

    /**
     * Atualizar CPF/CNPJ do usuário
     */
    public function updateCpfCnpj(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $this->repository->validateCpfCnpj($request->all(), $user);
        
        $this->repository->updateCpfCnpj($validated, $user);

        return redirect()->route('dashboard.index')
            ->with('success', 'CPF/CNPJ atualizado com sucesso!');
    }

    /**
     * Processar registro de novo usuário
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $this->repository->validateRegistration($request->all());
        
        $result = $this->repository->register($validated);

        return redirect()->route('dashboard.index')
            ->with('success', $result['message']);
    }

    /**
     * Realizar logout
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->repository->logout();
        
        return redirect('/')->with('success', 'Você saiu do sistema.');
    }

    /**
     * Atualizar profissão (para usuários do Google)
     */
    public function updateProfession(Request $request): RedirectResponse
    {
        $this->repository->validateProfession($request->all());
        
        $user = $this->repository->updateProfession($request->profession);

        if ($this->repository->needsCpfCnpj($user)) {
            return redirect()->route('cpf.cnpj.form')
                ->with('info', 'Agora informe seu CPF ou CNPJ para continuar.');
        }

        return redirect()->route('dashboard.index')
            ->with('success', 'Perfil atualizado! Bem-vindo ao Meajudaped.');
    }
}