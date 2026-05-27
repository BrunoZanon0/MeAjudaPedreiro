<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserSessionService
{
    const CACHE_PREFIX = 'user:session:';
    const CACHE_TTL = 86400; // 24 horas

    /**
     * Armazenar dados do usuário no Redis
     */
    public static function cacheUserData(User $user)
    {
        $cacheKey = self::CACHE_PREFIX . $user->id;
        
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profession' => $user->profession,
            'cpf' => $user->CPF,
            'cnpj' => $user->CNPJ,
            'google_id' => $user->google_id,
            'avatar' => $user->avatar,
        ];

        Cache::store('redis')->put($cacheKey, $userData, self::CACHE_TTL);
    }

    /**
     * Obter dados do usuário do Redis
     */
    public static function getUserData($userId)
    {
        $cacheKey = self::CACHE_PREFIX . $userId;
        return Cache::store('redis')->get($cacheKey);
    }

    /**
     * Verificar se o usuário tem CPF ou CNPJ preenchido via Redis
     */
    public static function hasCpfOrCnpj($userId): bool
    {
        $userData = self::getUserData($userId);
        
        if (!$userData) {
            // Se não está no cache, buscar do banco e cachear
            $user = User::find($userId);
            if ($user) {
                self::cacheUserData($user);
                return !empty($user->CPF) || !empty($user->CNPJ);
            }
            return false;
        }

        return !empty($userData['cpf']) || !empty($userData['cnpj']);
    }

    /**
     * Obter profissão do usuário via Redis
     */
    public static function getProfession($userId)
    {
        $userData = self::getUserData($userId);
        
        if (!$userData) {
            // Se não está no cache, buscar do banco
            $user = User::find($userId);
            if ($user) {
                self::cacheUserData($user);
                return $user->profession;
            }
            return null;
        }

        return $userData['profession'];
    }

    /**
     * Invalidar cache do usuário
     */
    public static function invalidateUserCache($userId)
    {
        $cacheKey = self::CACHE_PREFIX . $userId;
        Cache::store('redis')->forget($cacheKey);
    }

    /**
     * Atualizar campo específico do usuário no cache
     */
    public static function updateUserCache($userId, $field, $value)
    {
        $cacheKey = self::CACHE_PREFIX . $userId;
        $userData = Cache::store('redis')->get($cacheKey);

        if ($userData) {
            $userData[$field] = $value;
            Cache::store('redis')->put($cacheKey, $userData, self::CACHE_TTL);
        }
    }
}
