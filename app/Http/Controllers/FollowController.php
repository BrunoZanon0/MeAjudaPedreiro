<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Repository\FollowRepository;
use App\Http\Interfaces\FollowControllerInterface;

class FollowController extends Controller implements FollowControllerInterface
{
    protected $repository;

    public function __construct(FollowRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Alternar seguir/desseguir (toggle)
     */
    public function toggleFollow(User $user): JsonResponse
    {
        $result = $this->repository->toggleFollow($user);
        
        return response()->json([
            'success' => $result['success'],
            'following' => $result['following'] ?? null,
            'followers_count' => $result['followers_count'] ?? null,
            'message' => $result['message']
        ], $result['code']);
    }

    /**
     * Seguir usuário (método específico)
     */
    public function follow(User $user): JsonResponse
    {
        $result = $this->repository->follow($user);
        
        return response()->json([
            'success' => $result['success'],
            'following' => $result['following'] ?? null,
            'followers_count' => $result['followers_count'] ?? null,
            'message' => $result['message']
        ], $result['code']);
    }

    /**
     * Desseguir usuário (método específico)
     */
    public function unfollow(User $user): JsonResponse
    {
        $result = $this->repository->unfollow($user);
        
        return response()->json([
            'success' => $result['success'],
            'following' => $result['following'] ?? null,
            'followers_count' => $result['followers_count'] ?? null,
            'message' => $result['message']
        ], $result['code']);
    }

    /**
     * Verificar se o usuário logado segue outro
     */
    public function checkFollow(User $user): JsonResponse
    {
        $result = $this->repository->checkFollow($user);
        
        return response()->json($result);
    }

    /**
     * Listar seguidores de um usuário
     */
    public function followers(User $user): View|JsonResponse
    {
        $data = $this->repository->getFollowersWithMutualStatus($user);
        
        if (request()->wantsJson()) {
            return response()->json($data);
        }
        
        return view('users.followers', [
            'user' => $user,
            'followers' => $data['followers'],
            'mutual_status' => $data['mutual_status']
        ]);
    }

    /**
     * Listar quem o usuário segue
     */
    public function following(User $user): View|JsonResponse
    {
        $data = $this->repository->getFollowingWithMutualStatus($user);
        
        if (request()->wantsJson()) {
            return response()->json($data);
        }
        
        return view('users.following', [
            'user' => $user,
            'following' => $data['following'],
            'mutual_status' => $data['mutual_status']
        ]);
    }

    /**
     * Get suggested users to follow
     */
    public function suggestions(Request $request): View|JsonResponse
    {
        $limit = $request->get('limit', 10);
        $suggestions = $this->repository->getSuggestedUsers($limit);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'users' => $suggestions
            ]);
        }
        
        return view('users.suggestions', compact('suggestions'));
    }

    /**
     * Get mutual follows
     */
    public function mutualFollows(User $user): View|JsonResponse
    {
        $mutualFollows = $this->repository->getMutualFollows($user);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'users' => $mutualFollows
            ]);
        }
        
        return view('users.mutual', compact('user', 'mutualFollows'));
    }
}