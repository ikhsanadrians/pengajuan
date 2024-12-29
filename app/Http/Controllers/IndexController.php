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



    public function userIndex(){
        $transaksis = $this->pengajuanRepository->getAllPengajuanBarangsUser();
        $barangs = $this->barangRepository->getAllBarangs();
        $departements = $this->departemenRepository->getAllDepartements();
        $statutes = DB::table('status')->get();
        $satuan = DB::table('satuan')->get();

        $requestCount = $transaksis->where('status_id', 1)->count();
        $ditinjauCount = $transaksis->where('status_id', 2)->count();
        $approvedCount = $transaksis->where('status_id', 3)->count();
        $ditolakCount = $transaksis->where('status_id', 4)->count();

        return Inertia::render('User/Dashboard', [
            "transaksis" => $transaksis,
            'barangs' => $barangs,
            'departements' => $departements,
            'statuses' => $statutes,
            'requestCount' => $requestCount,
            'ditinjauCount' => $ditinjauCount,
            'approvedCount' => $approvedCount,
            'ditolakCount' => $ditolakCount,
            'satuans' => $satuan
        ]);
    }


    public function filterPengajuanUser(Request $request){

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