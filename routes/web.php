<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes (Login)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/admin',    [IndexController::class, 'adminIndex'])->name('admin');
    });

    // Owner Routes - Only accessible by users with owner role
    Route::middleware('owner')->group(function () {
        Route::get('/owner', [IndexController::class, 'ownerIndex'])->name('owner');
    });

    // User Routes - Only accessible by users with user role
    Route::middleware('user')->group(function () {
        Route::get('/', [IndexController::class, 'userIndex'])->name('user');
        Route::prefix('/user')->group(function(){
            Route::post('/simpan-pengajuan-user', [PengajuanController::class, 'simpanPengajuanUser'])->name('simpan-pengajuan-user');
            Route::post('/get-detail-pengajuan', [PengajuanController::class,'getDetailPengajuan'])->name('get-detail-pengajuan-user');
            Route::delete('/delete-pengajuan',[PengajuanController::class,'deletePengajuan'])->name('delete-pengajuan-user');
        });
    });