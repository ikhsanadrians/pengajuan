<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __construct(TransaksiRepository $transaksiRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
    }



    public function adminIndex(){
        // $transaksis = $this->transaksiRepository->getAllTransaksisAdmin();
        return Inertia::render('Admin/Dashboard');
    }


    public function userIndex(){
        $transaksis = $this->transaksiRepository->getAllTransaksisUser();

        return Inertia::render('User/Dashboard', ["transaksis" => $transaksis]);
    }
    public function ownerIndex(){
        return Inertia::render('Owner/Dashboard');
    }

}