<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CetakanController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes (Login)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost'])->name('loginPost');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



    // Owner Routes - Only accessible by users with owner role
    Route::middleware('owner')->group(function () {
        Route::get('/owner', [IndexController::class, 'ownerIndex'])->name('owner');
    });

    // User Routes - Only accessible by users with user role
    Route::middleware('user')->group(function () {
        Route::get('/', [IndexController::class, 'userIndex'])->name('user');
        Route::prefix('/user')->group(function(){
            Route::post('/simpan-pengajuan-user', [PengajuanController::class, 'simpanPengajuanUser'])->name('simpan-pengajuan-user');
            Route::post('/filter-pengajuan',[IndexController::class, 'filterPengajuanUser'])->name('filter-pengajuan-user');
            Route::post('/get-detail-pengajuan', [PengajuanController::class,'getDetailPengajuan'])->name('get-detail-pengajuan-user');
            Route::delete('/delete-pengajuan',[PengajuanController::class,'deletePengajuan'])->name('delete-pengajuan-user');
        });
    });

    Route::middleware('admin')->group(function () {
        Route::prefix('admin')->group(function(){
            Route::get('/',    [AdminController::class, 'adminIndex'])->name('admin');
            Route::post('/filter-pengajuan',[AdminController::class, 'filterPengajuanUser'])->name('filter-pengajuan-admin');

            Route::prefix('users')->group(function(){
                Route::get('/',   [AdminController::class, 'adminUsersIndex'])->name('admin.userIndex');
                Route::post('/add',  [AdminController::class, 'adminUsersAdd'])->name('admin.userAdd');
                Route::post('/delete',  [AdminController::class, 'adminUsersDelete'])->name('admin.userDelete');
            });

            Route::prefix('barang')->group(function(){
                Route::get('/',   [BarangController::class, 'adminBarangsIndex'])->name('admin.BarangsIndex');
                Route::post('/add',  [BarangController::class, 'adminBarangsAdd'])->name('admin.BarangsAdd');
                Route::post('/delete',  [BarangController::class, 'adminBarangsDelete'])->name('admin.BarangsDelete');
            });

            Route::prefix('kategori')->group(function(){
                Route::get('/',   [BarangController::class, 'adminKategoriIndex'])->name('admin.KategoriIndex');
                Route::post('/add',  [BarangController::class, 'adminKategoriAdd'])->name('admin.KategoriAdd');
                Route::post('/delete',  [BarangController::class, 'adminKategoriDelete'])->name('admin.KategoriDelete');
            });

            Route::prefix('departement')->group(function(){
                Route::get('/',   [BarangController::class, 'adminDepartementIndex'])->name('admin.DepartementIndex');
                Route::post('/add',  [BarangController::class, 'adminDepartementAdd'])->name('admin.DepartementAdd');
                Route::post('/delete',  [BarangController::class, 'adminDepartementDelete'])->name('admin.DepartementDelete');
            });




        });
    });


Route::get('tools/cetakan-detail-pengajuan-user/{code}', [CetakanController::class, 'cetakanDetailPengajuanUser'])->name('cetakan-detail-pengajuan-user');
