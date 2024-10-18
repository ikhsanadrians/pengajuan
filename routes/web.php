<?php

use App\Http\Controllers\IndexController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');


    Route::middleware(['auth'])->group(function () {
        // Rute yang memerlukan role admin
        Route::middleware(['check.role:admin'])->group(function () {
            Route::get('/admin', [IndexController::class, 'adminIndex'])->name('admin');
        });

        // Rute yang memerlukan role owner
        Route::middleware(['check.role:owner'])->group(function () {
            Route::get('/owner', [IndexController::class, 'ownerIndex'])->name('owner');
        });

        // Rute umum untuk user yang sudah login
        Route::get('/', [IndexController::class, 'userIndex'])->name('user');
    });
