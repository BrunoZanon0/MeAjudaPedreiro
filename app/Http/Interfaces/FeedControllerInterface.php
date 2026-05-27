<?php
// app/Http/Interfaces/FeedControllerInterface.php

namespace App\Http\Interfaces;

use Illuminate\View\View;

interface FeedControllerInterface
{
    /**
     * Display the feed page with all trabalhos.
     *
     * @return View
     */
    public function index(): View;
}