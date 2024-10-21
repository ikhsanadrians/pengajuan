<?php

namespace App\Http\Controllers;

use App\Repositories\BarangRepository;
use App\Repositories\DepartemenRepository;
use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __construct(TransaksiRepository $transaksiRepository, BarangRepository $barangRepository, DepartemenRepository $departemenRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
        $this->barangRepository = $barangRepository;
        $this->departemenRepository = $departemenRepository;
    }



    public function adminIndex(){
        // $transaksis = $this->transaksiRepository->getAllTransaksisAdmin();
        return Inertia::render('Admin/Dashboard');
    }


    public function userIndex(){
        $transaksis = $this->transaksiRepository->getAllTransaksisUser();
        $barangs = $this->barangRepository->getAllBarangs();
        $departements = $this->departemenRepository->getAllDepartements();

        return Inertia::render('User/Dashboard', ["transaksis" => $transaksis, 'barangs' => $barangs, 'departements' => $departements]);
    }
    public function ownerIndex(){
        return Inertia::render('Owner/Dashboard');
    }

}