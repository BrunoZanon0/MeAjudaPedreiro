<?php

namespace App\Http\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\UserSessionService;

class AuthRepository
{
    /**
     * @var User|null
     */
    protected $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user ?? Auth::user();
    }

    /**
     * Set the authenticated user
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get current user
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Attempt to authenticate user
     */
    public function authenticate(array $credentials, $remember = false): array
    {
        // Garantir que $remember seja booleano
        $remember = filter_var($remember, FILTER_VALIDATE_BOOLEAN);
        
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Cache user data in Redis
            UserSessionService::cacheUserData($user);
            
            return [
                'success' => true,
                'user' => $user,
                'message' => 'Bem-vindo de volta, ' . $user->name . '!'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'As credenciais informadas são inválidas.'
        ];
    }

    /**
     * Check if user needs to fill CPF/CNPJ
     */
    public function needsCpfCnpj(?User $user = null): bool
    {
        $user = $user ?? $this->user;
        return $user && empty($user->CPF) && empty($user->CNPJ);
    }

    /**
     * Check if user has CPF or CNPJ filled
     */
    public function hasCpfOrCnpj(?User $user = null): bool
    {
        $user = $user ?? $this->user;
        return $user && (!empty($user->CPF) || !empty($user->CNPJ));
    }

    /**
     * Validate CPF/CNPJ data
     */
    public function validateCpfCnpj(array $data, User $user): array
    {
        return validator($data, [
            'CPF' => 'nullable|string|unique:users,CPF,' . $user->id . '|required_without:CNPJ',
            'CNPJ' => 'nullable|string|unique:users,CNPJ,' . $user->id . '|required_without:CPF',
        ], [
            'CPF.required_without' => 'Informe CPF ou CNPJ.',
            'CNPJ.required_without' => 'Informe CPF ou CNPJ.',
        ])->validate();
    }

    /**
     * Update user's CPF/CNPJ
     */
    public function updateCpfCnpj(array $data, ?User $user = null): User
    {
        $user = $user ?? $this->user;
        
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        $user->CPF = $data['CPF'] ?? $user->CPF;
        $user->CNPJ = $data['CNPJ'] ?? $user->CNPJ;
        $user->save();
        
        // Update cache in Redis
        UserSessionService::cacheUserData($user);
        
        return $user;
    }

    /**
     * Validate registration data
     */
    public function validateRegistration(array $data): array
    {
        return validator($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'CPF' => 'nullable|string|unique:users,CPF|required_without:CNPJ',
            'CNPJ' => 'nullable|string|unique:users,CNPJ|required_without:CPF',
            'profession' => 'required|in:consultor,pedreiro',
            'password' => 'required|min:6|confirmed',
        ], [
            'CPF.required_without' => 'Informe CPF ou CNPJ.',
            'CNPJ.required_without' => 'Informe CPF ou CNPJ.',
            'email.unique' => 'Este email já está cadastrado.',
        ])->validate();
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'tag' => '@' . strtolower(str_replace(' ', '_', $data['name'])),
            'CPF' => $data['CPF'] ?? null,
            'CNPJ' => $data['CNPJ'] ?? null,
            'profession' => $data['profession'],
            'password' => Hash::make($data['password']),
        ]);
        
        // Cache user data in Redis
        UserSessionService::cacheUserData($user);
        
        return $user;
    }

    /**
     * Register and login user
     */
    public function register(array $data): array
    {
        $user = $this->createUser($data);
        Auth::login($user);
        
        return [
            'success' => true,
            'user' => $user,
            'message' => 'Conta criada com sucesso! Bem-vindo, ' . $user->name . '!'
        ];
    }

    /**
     * Logout user
     */
    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * Validate profession update
     */
    public function validateProfession(array $data): array
    {
        return validator($data, [
            'profession' => 'required|in:consultor,pedreiro',
        ])->validate();
    }

    /**
     * Update user's profession
     */
    public function updateProfession(string $profession, ?User $user = null): User
    {
        $user = $user ?? $this->user;
        
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        $user->profession = $profession;
        $user->save();
        
        // Update cache in Redis
        UserSessionService::updateUserCache($user->id, 'profession', $profession);
        
        return $user;
    }

    /**
     * Get redirect after login based on user status
     */
    public function getLoginRedirect(User $user): string
    {
        if ($this->needsCpfCnpj($user)) {
            return route('cpf.cnpj.form');
        }
        
        return route('dashboard.index');
    }

    /**
     * Get user by email
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Check if email already exists
     */
    public function emailExists(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    /**
     * Generate user tag from name
     */
    public function generateTag(string $name): string
    {
        return '@' . strtolower(str_replace(' ', '_', $name));
    }

    /**
     * Update user's password
     */
    public function updatePassword(User $user, string $password): User
    {
        $user->password = Hash::make($password);
        $user->save();
        
        return $user;
    }

    /**
     * Verify if password matches
     */
    public function verifyPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }

    /**
     * Get user stats (for dashboard)
     */
    public function getUserStats(?User $user = null): array
    {
        $user = $user ?? $this->user;
        
        if (!$user) {
            return [
                'trabalhos_count' => 0,
                'followers_count' => 0,
                'following_count' => 0
            ];
        }
        
        return [
            'trabalhos_count' => $user->trabalhosFeitos()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count()
        ];
    }
}