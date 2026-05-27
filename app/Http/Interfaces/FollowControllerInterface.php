<?php

namespace App\Http\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

interface FollowControllerInterface
{
    /**
     * Toggle follow/unfollow for a user
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function toggleFollow(User $user): JsonResponse;

    /**
     * Follow a user
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function follow(User $user): JsonResponse;

    /**
     * Unfollow a user
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function unfollow(User $user): JsonResponse;

    /**
     * Check if authenticated user follows another user
     * 
     * @param User $user
     * @return JsonResponse
     */
    public function checkFollow(User $user): JsonResponse;

    /**
     * List followers of a user
     * 
     * @param User $user
     * @return View|JsonResponse
     */
    public function followers(User $user): View|JsonResponse;

    /**
     * List users that a user follows
     * 
     * @param User $user
     * @return View|JsonResponse
     */
    public function following(User $user): View|JsonResponse;

    /**
     * Get suggested users to follow
     * 
     * @param Request $request
     * @return View|JsonResponse
     */
    public function suggestions(Request $request): View|JsonResponse;

    /**
     * Get mutual follows (users that follow each other)
     * 
     * @param User $user
     * @return View|JsonResponse
     */
    public function mutualFollows(User $user): View|JsonResponse;
}