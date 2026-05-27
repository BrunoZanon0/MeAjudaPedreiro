<?php
// app/Http/Controllers/FeedController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TrabalhoFeito;
use App\Services\UserSessionService;
use App\Http\Interfaces\FeedControllerInterface;
use Illuminate\View\View; // Importante: use a View correta

class FeedController extends Controller implements FeedControllerInterface
{
    /**
     * Display the feed page with all trabalhos.
     *
     * @return View
     */
    public function index(): View 
    {
        $trabalho = TrabalhoFeito::with('user')->latest()->get();
        
        return view('feed.index', compact('trabalho'));
    }
}