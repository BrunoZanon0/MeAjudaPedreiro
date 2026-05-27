<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\AuthController;
use App\Http\Interfaces\AuthControllerInterface;
use App\Http\Interfaces\FeedControllerInterface;
use App\Http\Interfaces\DashboardControllerInterface;
use App\Http\Interfaces\FollowControllerInterface;
use App\Http\Repository\TrabalhoFeitoRepository;
use App\Http\Repository\FollowRepository;
use App\Http\Repository\DashboardRepository;
use App\Http\Repository\AuthRepository;


use App\Models\TrabalhoFeito;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthControllerInterface::class, AuthController::class);
        $this->app->bind(FeedControllerInterface::class, \App\Http\Controllers\FeedController::class);
        $this->app->bind(DashboardControllerInterface::class, \App\Http\Controllers\DashboardController::class);
        $this->app->bind(TrabalhoFeitoRepository::class, function ($app) {return new TrabalhoFeitoRepository(new TrabalhoFeito());});
        $this->app->bind(FollowControllerInterface::class, FollowController::class);
        $this->app->singleton(FollowRepository::class, function ($app) {return new FollowRepository();});
        $this->app->singleton(DashboardRepository::class, function ($app) { return new DashboardRepository();});
        $this->app->singleton(AuthRepository::class, function ($app) { return new AuthRepository(); });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
