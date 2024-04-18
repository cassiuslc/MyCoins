<?php

namespace App\Providers\Auth;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\Auth\UserInterface;
use App\Repositories\Auth\UserReposiotry;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class,UserReposiotry::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
