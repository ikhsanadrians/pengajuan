<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\BarangRepository;
use App\Repositories\Interfaces\BarangRepositoryInterface;
use App\Repositories\PengajuanRepository;
use App\Repositories\Interfaces\PengajuanBarangRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\TransaksiRepositoryInterface;
use App\Repositories\TransaksiRepository;
use App\Repositories\Interfaces\TransaksiStokRepositoryInterface;
use App\Repositories\TransaksiStokRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BarangRepositoryInterface::class, BarangRepository::class);
        $this->app->bind(PengajuanBarangRepositoryInterface::class, PengajuanRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TransaksiRepositoryInterface::class, TransaksiRepository::class);
        $this->app->bind(TransaksiStokRepositoryInterface::class, TransaksiStokRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
