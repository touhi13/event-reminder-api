<?php

namespace App\Providers;

use App\Repositories\Auth\AuthInterface;
use App\Repositories\Auth\AuthRepo;
use App\Repositories\Event\EventInterface;
use App\Repositories\Event\EventRepo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthInterface::class,
            AuthRepo::class
        );

        $this->app->bind(
            EventInterface::class,
            EventRepo::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
