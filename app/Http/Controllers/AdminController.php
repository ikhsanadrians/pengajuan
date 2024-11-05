<?php

namespace App\Http\Controllers;

use App\Repositories\BarangRepository;
use App\Repositories\DepartemenRepository;
use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;
use Inertia\Inertia;
use App\Repositories\PengajuanRepository;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{

    public function __construct(TransaksiRepository $transaksiRepository, BarangRepository $barangRepository, DepartemenRepository $departemenRepository, PengajuanRepository $pengajuanRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
        $this->barangRepository = $barangRepository;
        $this->departemenRepository = $departemenRepository;
        $this->pengajuanRepository = $pengajuanRepository;
    }

    public function adminIndex(){
        $transaksis = $this->pengajuanRepository->getAllPengajuanBarangsAdmin();
        $barangs = $this->barangRepository->getAllBarangs();
        $departements = $this->departemenRepository->getAllDepartements();
        $statutes = DB::table('status')->get();


        return Inertia::render('Admin/Dashboard', ["transaksis" => $transaksis, 'barangs' => $barangs, 'departements' => $departements, 'statuses' => $statutes]);
    }


    public function filterPengajuanAdmin(Request $request){

        $params = $request->input('params', []);

        $startDate = $params['start_date'] ?? null;
        $endDate = $params['end_date'] ?? null;
        $status = $params['status'] ?? null;
        $searchQuery = $params['search_query'] ?? null;

        $filteredTransaksis = $this->pengajuanRepository->getFilteredPengajuanBarangs($startDate, $endDate, $status, $searchQuery);


        return response()->json([
            'transaksis' => $filteredTransaksis
        ]);
    }


}
