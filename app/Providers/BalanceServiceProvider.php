<?php

namespace App\Providers;

use App\Interfaces\BalanceServiceInterface;
use App\Services\BalanceService;
use Illuminate\Support\ServiceProvider;

class BalanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BalanceServiceInterface::class, BalanceService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
