<?php
// app/Http/Interfaces/AuthControllerInterface.php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

interface AuthControllerInterface
{
    /**
     * @return View
     * 
     * @description Exibe o formulário de login
     */
    public function login(): View;
    
    /**
     * @param Request $request
     * @return RedirectResponse
     * 
     * @description Autentica o usuário com email e senha
     * @validation email: required|email, password: required
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(Request $request): RedirectResponse;
    
    /**
     * @return View
     * 
     * @description Exibe o formulário de registro
     */
    public function showRegisterForm(): View;
    
    /**
     * @return View|RedirectResponse
     * 
     * @description Exibe o formulário de CPF/CNPJ ou redireciona se já preenchido
     */
    public function showCpfCnpjForm(): View|RedirectResponse;
    
    /**
     * @param Request $request
     * @return RedirectResponse
     * 
     * @description Atualiza CPF ou CNPJ do usuário autenticado
     * @validation CPF: nullable|string|unique, CNPJ: nullable|string|unique
     */
    public function updateCpfCnpj(Request $request): RedirectResponse;
    
    /**
     * @param Request $request
     * @return RedirectResponse
     * 
     * @description Registra um novo usuário e faz login automaticamente
     * @validation name: required, email: required|email|unique, 
     *              profession: required|in:consultor,pedreiro,
     *              password: required|min:6|confirmed
     */
    public function register(Request $request): RedirectResponse;
    
    /**
     * @param Request $request
     * @return RedirectResponse
     * 
     * @description Realiza logout, invalida sessão e regenera token
     */
    public function logout(Request $request): RedirectResponse;
    
    /**
     * @param Request $request
     * @return RedirectResponse
     * 
     * @description Atualiza a profissão (usado principalmente para usuários do Google)
     * @validation profession: required|in:consultor,pedreiro
     */
    public function updateProfession(Request $request): RedirectResponse;
}