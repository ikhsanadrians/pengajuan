<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CetakanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\UserController;


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
            Route::post('/filter-pengajuan',[AdminController::class, 'filterPengajuanAdmin'])->name('filter-pengajuan-admin');
            Route::post('/get-detail-pengajuan-admin', [PengajuanController::class,'getDetailPengajuan'])->name('get-detail-pengajuan-admin');
            Route::post('/reject-per-transaksi', [TransaksiController::class,'rejectTransaksi'])->name('reject-per-transaksi');
            Route::post('/simpan-verif-pengajuan', [AdminController::class,'SimpanVerifPengajuan'])->name('simpan-verif-pengajuan');
            Route::post('/reject-per-pengajuan', [PengajuanController::class,'rejectPengajuan'])->name('reject-per-pengajuan');


            Route::prefix('users')->group(function(){
                Route::get('/',   [AdminController::class, 'adminUsersIndex'])->name('admin.userIndex');
                Route::post('/{id}',[UserController::class, 'showUser'])->name('admin.showUser');
                Route::post('/{id}/update', [UserController::class, 'updateUser'])->name('admin.update');
                Route::post('/add',  [UserController::class, 'storeUser'])->name('admin.addUserPost');
                Route::post('/delete',  [UserController::class, 'deleteUser'])->name('admin.userDelete');
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

            Route::prefix('departements')->group(function(){
                Route::get('/',   [DepartementController::class, 'adminDepartementIndex'])->name('admin.DepartementIndex');
                Route::get('/{id}', [DepartementController::class, 'adminDepartementShow'])->name('admin.DepartementShow');
                Route::post('/add',  [DepartementController::class, 'adminDepartementAdd'])->name('admin.DepartementAdd');
                Route::post('/delete',  [DepartementController::class, 'adminDepartementDelete'])->name('admin.DepartementDelete');
            });




        });
    });


Route::get('tools/cetakan-detail-pengajuan-user/{code}', [CetakanController::class, 'cetakanDetailPengajuanUser'])->name('cetakan-detail-pengajuan-user');