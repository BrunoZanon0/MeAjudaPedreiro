<?php

namespace App\Http\Repository;

use App\Models\User;
use App\Models\TrabalhoFeito;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DashboardRepository
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
     * Get user's trabalhos with pagination
     */
    public function getUserTrabalhos(?int $userId = null, int $perPage = 10): LengthAwarePaginator
    {
        $userId = $userId ?? ($this->user ? $this->user->id : null);
        
        if (!$userId) {
            return new LengthAwarePaginator([], 0, $perPage);
        }
        
        return TrabalhoFeito::where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get user's trabalhos as collection (without pagination)
     */
    public function getUserTrabalhosCollection(?int $userId = null): Collection
    {
        $userId = $userId ?? ($this->user ? $this->user->id : null);
        
        if (!$userId) {
            return collect();
        }
        
        return TrabalhoFeito::where('user_id', $userId)
            ->latest()
            ->get();
    }

    /**
     * Search pedreiros by query string
     */
    public function searchPedreiros(string $query): Collection
    {
        return User::where('profession', 'pedreiro')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('tag', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->withCount('trabalhosFeitos')
            ->get();
    }

    /**
     * Get pedreiro suggestions for consultant
     */
    public function getPedreiroSuggestions(?string $query = null): Collection
    {
        if ($query) {
            return $this->searchPedreiros($query);
        }
        
        // Se não tiver busca, retorna os últimos 10 pedreiros
        return User::where('profession', 'pedreiro')
            ->withCount('trabalhosFeitos')
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * Get user profile data with counts
     */
    public function getUserProfileData(?User $user = null): array
    {
        $user = $user ?? $this->user;
        
        if (!$user) {
            return [
                'user' => null,
                'trabalhos' => collect(),
                'totalSeguidores' => 0,
                'totalSeguindo' => 0,
                'isFollowing' => false
            ];
        }

        $trabalhos = $this->getUserTrabalhosCollection($user->id);
        
        // Carregar contagens
        $user->loadCount(['followers', 'following']);
        
        // Verificar se o usuário logado segue este perfil
        $isFollowing = false;
        if (Auth::check() && $this->user && $this->user->id !== $user->id) {
            $isFollowing = $this->user->isFollowing($user);
        }

        return [
            'user' => $user,
            'trabalhos' => $trabalhos,
            'totalSeguidores' => $user->followers_count,
            'totalSeguindo' => $user->following_count,
            'isFollowing' => $isFollowing
        ];
    }

    /**
     * Get user by tag
     */
    public function getUserByTag(string $tag): ?User
    {
        return User::where('tag', '@' . ltrim($tag, '@'))->first();
    }

    /**
     * Get user by tag or fail
     */
    public function getUserByTagOrFail(string $tag): User
    {
        return User::where('tag', '@' . ltrim($tag, '@'))->firstOrFail();
    }

    /**
     * Get trabalho by user and trabalho id
     */
    public function getUserTrabalhoById(User $user, int $trabalhoId): ?TrabalhoFeito
    {
        return TrabalhoFeito::where('id', $trabalhoId)
            ->where('user_id', $user->id)
            ->first();
    }

    /**
     * Get trabalho by user and trabalho id or fail
     */
    public function getUserTrabalhoByIdOrFail(User $user, int $trabalhoId): TrabalhoFeito
    {
        return TrabalhoFeito::where('id', $trabalhoId)
            ->where('user_id', $user->id)
            ->firstOrFail();
    }

    /**
     * Update user profile
     */
    public function updateProfile(array $data, ?User $user = null): User
    {
        $user = $user ?? $this->user;
        
        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->name = $data['name'] ?? $user->name;
        $user->cpf = $data['cpf'] ?? $user->cpf;
        $user->cnpj = $data['cnpj'] ?? $user->cnpj;
        
        // Update tag based on name
        if (isset($data['name'])) {
            $user->tag = '@' . strtolower(str_replace(' ', '_', $data['name']));
        }
        
        // Update password if provided
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        
        $user->save();
        
        return $user;
    }

    /**
     * Validate profile update data
     */
    public function validateProfileData(array $data, User $user): array
    {
        return validator($data, [
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:20|unique:users,cpf,' . $user->id,
            'cnpj' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
        ])->validate();
    }

    /**
     * Get dashboard stats for user
     */
    public function getDashboardStats(?User $user = null): array
    {
        $user = $user ?? $this->user;
        
        if (!$user) {
            return [
                'total_trabalhos' => 0,
                'total_seguidores' => 0,
                'total_seguindo' => 0,
                'media_avaliacao' => 0
            ];
        }
        
        $trabalhos = $this->getUserTrabalhosCollection($user->id);
        
        return [
            'total_trabalhos' => $trabalhos->count(),
            'total_seguidores' => $user->followers()->count(),
            'total_seguindo' => $user->following()->count(),
            'media_avaliacao' => $trabalhos->avg('avaliacao') ?? 0
        ];
    }

    /**
     * Get recent trabalhos from followed users (for feed)
     */
    public function getFollowedUsersTrabalhos(int $limit = 10): Collection
    {
        if (!$this->user) {
            return collect();
        }
        
        $followingIds = $this->user->following()->pluck('users.id');
        
        if ($followingIds->isEmpty()) {
            return collect();
        }
        
        return TrabalhoFeito::whereIn('user_id', $followingIds)
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Check if user can access trabalho
     */
    public function canAccessTrabalho(TrabalhoFeito $trabalho, ?User $user = null): bool
    {
        $user = $user ?? $this->user;
        
        if (!$user) {
            return false;
        }
        
        // Dono do trabalho pode acessar
        if ($trabalho->user_id === $user->id) {
            return true;
        }
        
        // Consultor pode ver trabalhos de pedreiros que segue
        if ($user->profession === 'consultor' && $user->isFollowing($trabalho->user)) {
            return true;
        }
        
        return false;
    }

    /**
     * Get popular pedreiros (mais seguidos)
     */
    public function getPopularPedreiros(int $limit = 5): Collection
    {
        return User::where('profession', 'pedreiro')
            ->withCount('followers')
            ->having('followers_count', '>', 0)
            ->orderBy('followers_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get latest pedreiros (novos)
     */
    public function getLatestPedreiros(int $limit = 5): Collection
    {
        return User::where('profession', 'pedreiro')
            ->latest()
            ->limit($limit)
            ->get();
    }
}