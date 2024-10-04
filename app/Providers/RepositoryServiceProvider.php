<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TransaksiRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TransaksiRepository::class, function ($app) {
            return new TransaksiRepository();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}