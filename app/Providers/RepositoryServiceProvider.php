<?php

namespace App\Providers;

use App\Interfaces\Auth\UserInterface;
use App\Interfaces\BalanceServiceInterface;
use App\Interfaces\WalletInterface;
use App\Repositories\Auth\UserReposiotry;
use App\Repositories\WalletReposiotry;
use App\Services\BalanceService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class,UserReposiotry::class);
        $this->app->bind(WalletInterface::class,WalletReposiotry::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
