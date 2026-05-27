<?php

namespace App\Http\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class FollowRepository
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
     * Check if user is trying to follow themselves
     */
    public function isSelfFollow(User $targetUser): bool
    {
        return $this->user && $this->user->id === $targetUser->id;
    }

    /**
     * Toggle follow/unfollow
     */
    public function toggleFollow(User $targetUser): array
    {
        if ($this->isSelfFollow($targetUser)) {
            return [
                'success' => false,
                'message' => 'Você não pode seguir a si mesmo.',
                'code' => 400
            ];
        }

        $isNowFollowing = $this->user->toggleFollow($targetUser);
        $this->loadFollowersCount($targetUser);

        return [
            'success' => true,
            'following' => $isNowFollowing,
            'followers_count' => $targetUser->followers_count,
            'message' => $isNowFollowing ? 'Agora você está seguindo.' : 'Você deixou de seguir.',
            'code' => 200
        ];
    }

    /**
     * Follow a user
     */
    public function follow(User $targetUser): array
    {
        if ($this->isSelfFollow($targetUser)) {
            return [
                'success' => false,
                'message' => 'Você não pode seguir a si mesmo.',
                'code' => 400
            ];
        }

        $following = $this->user->follow($targetUser);
        $this->loadFollowersCount($targetUser);

        return [
            'success' => true,
            'following' => $following,
            'followers_count' => $targetUser->followers_count,
            'message' => $following ? 'Usuário seguido com sucesso!' : 'Você já segue este usuário.',
            'code' => 200
        ];
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $targetUser): array
    {
        $unfollowed = $this->user->unfollow($targetUser);
        $this->loadFollowersCount($targetUser);

        return [
            'success' => true,
            'following' => false,
            'followers_count' => $targetUser->followers_count,
            'message' => $unfollowed ? 'Usuário deixado de seguir.' : 'Você não segue este usuário.',
            'code' => 200
        ];
    }

    /**
     * Check if current user follows target user
     */
    public function checkFollow(User $targetUser): array
    {
        $isFollowing = $this->user ? $this->user->isFollowing($targetUser) : false;
        
        return [
            'following' => $isFollowing,
            'followers_count' => $targetUser->followers()->count()
        ];
    }

    /**
     * Get user's followers with pagination
     */
    public function getFollowers(User $user, int $perPage = 20)
    {
        return $user->followers()->paginate($perPage);
    }

    /**
     * Get users that the user follows with pagination
     */
    public function getFollowing(User $user, int $perPage = 20)
    {
        return $user->following()->paginate($perPage);
    }

    /**
     * Get followers count
     */
    public function getFollowersCount(User $user): int
    {
        return $user->followers()->count();
    }

    /**
     * Get following count
     */
    public function getFollowingCount(User $user): int
    {
        return $user->following()->count();
    }

    /**
     * Load followers count for a user
     */
    public function loadFollowersCount(User $user): void
    {
        $user->loadCount('followers');
    }

    /**
     * Get mutual follows (users that follow each other)
     */
    public function getMutualFollows(User $user): Collection
    {
        $followingIds = $user->following()->pluck('users.id');
        return $user->followers()->whereIn('users.id', $followingIds)->get();
    }

    /**
     * Get suggested users to follow (users not followed yet)
     */
    public function getSuggestedUsers(int $limit = 10): Collection
    {
        if (!$this->user) {
            return User::where('id', '!=', 0)->limit($limit)->get();
        }

        $followingIds = $this->user->following()->pluck('users.id');
        $followingIds[] = $this->user->id;

        return User::whereNotIn('id', $followingIds)
            ->where('profession', 'pedreiro') // Ajuste conforme necessidade
            ->limit($limit)
            ->get();
    }

    /**
     * Get followers with mutual status
     */
    public function getFollowersWithMutualStatus(User $user): array
    {
        $followers = $this->getFollowers($user);
        
        if (!$this->user || $this->user->id === $user->id) {
            return [
                'followers' => $followers,
                'mutual_status' => []
            ];
        }

        $mutualStatus = [];
        foreach ($followers as $follower) {
            $mutualStatus[$follower->id] = $this->user->isFollowing($follower);
        }

        return [
            'followers' => $followers,
            'mutual_status' => $mutualStatus
        ];
    }

    /**
     * Get following with mutual status
     */
    public function getFollowingWithMutualStatus(User $user): array
    {
        $following = $this->getFollowing($user);
        
        if (!$this->user || $this->user->id === $user->id) {
            return [
                'following' => $following,
                'mutual_status' => []
            ];
        }

        $mutualStatus = [];
        foreach ($following as $followed) {
            $mutualStatus[$followed->id] = $followed->isFollowing($this->user);
        }

        return [
            'following' => $following,
            'mutual_status' => $mutualStatus
        ];
    }

    /**
     * Check if users are following each other
     */
    public function areMutualFollows(User $user1, User $user2): bool
    {
        return $user1->isFollowing($user2) && $user2->isFollowing($user1);
    }

    /**
     * Get top followers (most followed users)
     */
    public function getTopFollowers(int $limit = 10): Collection
    {
        return User::withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Sync follows (replace all follows with new list)
     */
    public function syncFollows(User $user, array $userIds): array
    {
        return $user->following()->sync($userIds);
    }

    /**
     * Get followers count for multiple users
     */
    public function getMultipleFollowersCount(Collection $users): Collection
    {
        return $users->loadCount('followers');
    }

    /**
     * Check if user follows any of the given users
     */
    public function followsAny(User $user, array $userIds): bool
    {
        return $user->following()->whereIn('following_id', $userIds)->exists();
    }

    /**
     * Get users that a user follows by profession
     */
    public function getFollowingByProfession(User $user, string $profession): Collection
    {
        return $user->following()
            ->where('profession', $profession)
            ->get();
    }
}