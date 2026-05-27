<?php
// app/Http/Interfaces/DashboardControllerInterface.php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

interface DashboardControllerInterface
{
    /**
     * Display the dashboard page.
     * 
     * If user is 'pedreiro': shows their trabalhos
     * If user is 'consultor': shows search form and results for pedreiros
     *
     * @param Request $request - Contains optional 'query' parameter for search
     * @return View - dashboard.dashboard view
     */
    public function index(Request $request): View;

    /**
     * Display the authenticated user's profile.
     * Shows user info, trabalhos, followers and following counts.
     *
     * @return View - dashboard.perfil view
     */
    public function showPerfil(): View;

    /**
     * Display the profile edit form.
     * Allows user to edit name, CPF, CNPJ, password, etc.
     *
     * @return View - dashboard.editar-perfil view
     */
    public function editPerfil(): View;

    /**
     * Display a specific trabalho from a user's profile.
     * Used to view trabalho details from profile page.
     *
     * @param string $tag - User's unique tag (e.g., @username)
     * @param int $id - Trabalho ID
     * @return View - trabalhos.show view
     */
    public function showTrabalhoFromPerfil(string $tag, int $id): View;

    /**
     * Update the authenticated user's profile.
     * Validates and updates name, CPF, CNPJ, password.
     *
     * @param Request $request - Contains name, cpf, cnpj, password (optional)
     * @return RedirectResponse - Redirects to profile page with success/error message
     */
    public function updatePerfil(Request $request): RedirectResponse;

    /**
     * Follow a user.
     * Prevents following self and duplicate follows.
     * Supports both AJAX and regular requests.
     *
     * @param int $id - ID of user to follow
     * @return RedirectResponse|JsonResponse - JSON for AJAX, Redirect for regular requests
     */
    public function seguir(int $id): RedirectResponse|JsonResponse;

    /**
     * Unfollow a user.
     * Prevents unfollowing self and checks if they are followed.
     * Supports both AJAX and regular requests.
     *
     * @param int $id - ID of user to unfollow
     * @return RedirectResponse|JsonResponse - JSON for AJAX, Redirect for regular requests
     */
    public function desseguir(int $id): RedirectResponse|JsonResponse;

    /**
     * Toggle follow/unfollow for a user.
     * Automatically determines if should follow or unfollow.
     * Supports both AJAX and regular requests.
     *
     * @param int $id - ID of user to toggle follow
     * @return RedirectResponse|JsonResponse - JSON for AJAX, Redirect for regular requests
     */
    public function toggleFollow(int $id): RedirectResponse|JsonResponse;

    /**
     * Check if the authenticated user follows another user.
     * Used for AJAX requests to update UI state.
     *
     * @param int $id - ID of user to check
     * @return JsonResponse - Returns following status and followers count
     */
    public function checkFollow(int $id): JsonResponse;

    /**
     * Display a user's profile by their unique tag.
     * Shows public profile with trabalhos, followers, following.
     *
     * @param string $tag - User's unique tag (e.g., @username)
     * @return View - dashboard.perfil view with user data
     */
    public function showPerfilWithTag(string $tag): View;
}