<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password','tag', 'CPF', 'CNPJ', 'profession', 'google_id', 'avatar', 'cidade', 'bio'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacionamento com trabalhos feitos
    public function trabalhosFeitos()
    {
        return $this->hasMany(TrabalhoFeito::class);
    }

    /**
     * RELACIONAMENTOS DE FOLLOW
     * Tabela: follows
     * follower_id = quem segue
     * following_id = quem é seguido
     */

    // Usuários que SEGUEM este usuário (meus seguidores)
    public function followers()
    {
        return $this->belongsToMany(
            User::class,           // Modelo relacionado
            'follows',             // Tabela pivot
            'following_id',        // FK da tabela atual na pivot (quem é seguido)
            'follower_id'          // FK do modelo relacionado na pivot (quem segue)
        )->withTimestamps();
    }

    // Usuários que este usuário SEGUE (quem eu sigo)
    public function following()
    {
        return $this->belongsToMany(
            User::class,           // Modelo relacionado
            'follows',             // Tabela pivot
            'follower_id',         // FK da tabela atual na pivot (quem segue)
            'following_id'         // FK do modelo relacionado na pivot (quem é seguido)
        )->withTimestamps();
    }

    /**
     * MÉTODOS AUXILIARES DE FOLLOW
     */

    // Verificar se o usuário atual segue outro usuário
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    // Verificar se o usuário atual é seguido por outro
    public function isFollowedBy(User $user)
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    // Seguir um usuário
    public function follow(User $user)
    {
        // Não pode seguir a si mesmo
        if ($this->id === $user->id) {
            return false;
        }

        // Verificar se já segue
        if (!$this->isFollowing($user)) {
            $this->following()->attach($user->id);
            return true;
        }

        return false;
    }

    // Desseguir um usuário
    public function unfollow(User $user)
    {
        // Verificar se segue
        if ($this->isFollowing($user)) {
            $this->following()->detach($user->id);
            return true;
        }

        return false;
    }

    // Alternar seguir/desseguir (toggle)
    public function toggleFollow(User $user)
    {
        if ($this->isFollowing($user)) {
            $this->unfollow($user);
            return false; // Retorna false se desseguiu
        } else {
            $this->follow($user);
            return true; // Retorna true se seguiu
        }
    }

    /**
     * MÉTODOS DE ACESSOR (GETTERS) PARA CONTAGENS
     */

    // Contagem de seguidores
    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    // Contagem de seguindo
    public function getFollowingCountAttribute()
    {
        return $this->following()->count();
    }

    // Alternativa usando loadCount (mais eficiente para múltiplos acessos)
    public function loadFollowersCount()
    {
        $this->loadCount('followers');
        return $this;
    }

    public function loadFollowingCount()
    {
        $this->loadCount('following');
        return $this;
    }
}