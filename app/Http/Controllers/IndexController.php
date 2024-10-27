<?php

namespace App\Http\Controllers;

use App\Repositories\BarangRepository;
use App\Repositories\DepartemenRepository;
use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;
use Inertia\Inertia;
use App\Repositories\PengajuanRepository;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function __construct(TransaksiRepository $transaksiRepository, BarangRepository $barangRepository, DepartemenRepository $departemenRepository, PengajuanRepository $pengajuanRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
        $this->barangRepository = $barangRepository;
        $this->departemenRepository = $departemenRepository;
        $this->pengajuanRepository = $pengajuanRepository;
    }



    public function adminIndex(){
        // $transaksis = $this->transaksiRepository->getAllTransaksisAdmin();
        return Inertia::render('Admin/Dashboard');
    }


    public function userIndex(){
        $transaksis = $this->pengajuanRepository->getAllPengajuanBarangsUser();
        $barangs = $this->barangRepository->getAllBarangs();
        $departements = $this->departemenRepository->getAllDepartements();
        $statutes = DB::table('status')->get();

        return Inertia::render('User/Dashboard', ["transaksis" => $transaksis, 'barangs' => $barangs, 'departements' => $departements, 'statuses' => $statutes]);
    }
    public function ownerIndex(){
        return Inertia::render('Owner/Dashboard');
    }

}