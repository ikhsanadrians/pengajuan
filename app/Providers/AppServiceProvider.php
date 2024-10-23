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
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BarangRepository::class, function ($app) {
            return new BarangRepository();
        });
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository();
        });
        $this->app->bind(TransaksiRepository::class, function ($app) {
            return new TransaksiRepository();
        });
        $this->app->bind(TransaksiStokRepository::class, function ($app) {
            return new TransaksiStokRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once app_path('Helpers/helpers.php');

        Inertia::share([
            'auth.user' => function () {
                return Auth::user() ? [
                    'id' => Auth::user()->id,
                    'username' => Auth::user()->username,
                    'role_id' => Auth::user()->role_id,
                ] : null;
            },
        ]);
    }
}