<?php

namespace App\Http\Controllers;

use App\Repositories\BarangRepository;
use App\Repositories\DepartemenRepository;
use App\Repositories\PengajuanRepository;
use App\Repositories\TransaksiRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class OwnerController extends Controller
{
    protected $transaksiRepository;
    protected $barangRepository;
    protected $departemenRepository;

    protected $pengajuanRepository;

    public function __construct(TransaksiRepository $transaksiRepository, BarangRepository $barangRepository, PengajuanRepository $pengajuanRepository, DepartemenRepository $departemenRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
        $this->pengajuanRepository = $pengajuanRepository;
        $this->barangRepository = $barangRepository;
        $this->departemenRepository = $departemenRepository;
    }

    public function ownerIndex()
    {
        $transaksis = $this->pengajuanRepository->getAllPengajuanBarangsOwner(); // Assuming you have this method
       
        $barangs = $this->barangRepository->getAllBarangs();
        $departements = $this->departemenRepository->getAllDepartements();

        
        $requestCount = $transaksis->where('status_id', 1)->count();
        $ditinjauCount = $transaksis->where('status_id', 2)->count();
        $approvedCount = $transaksis->where('status_id', 3)->count();
        $ditolakCount = $transaksis->where('status_id', 4)->count();


        return Inertia::render('Owner/Dashboard', [
            'transaksis' => $transaksis,
            'barangs' => $barangs,
            'departements' => $departements,
            'requestCount' => $requestCount,
            'ditinjauCount' => $ditinjauCount,
            'approvedCount' => $approvedCount,
            'ditolakCount' => $ditolakCount
        ]);
    }
 
    public function ownerAcceptPengajuan(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'uniqueId' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Approve the pengajuan using the repository
        $result = $this->pengajuanRepository->approvePengajuan(
            $request->uniqueId,
        );

        if (!$result) {
            return response()->json(['message' => 'Failed to approve transaction'], 500);
        }

        return response()->json(['message' => 'Transaction approved successfully'], 200);
    }


    
    public function filterPengajuanOwner(Request $request){

        $params = $request->input('params', []);

        $startDate = $params['start_date'] ?? null;
        $endDate = $params['end_date'] ?? null;
        $status = $params['status'] ?? null;
        $searchQuery = $params['search_query'] ?? null;

        $filteredTransaksis = $this->pengajuanRepository->getFilteredPengajuanBarangsOwner($startDate, $endDate, $status, $searchQuery);

        return response()->json([
            'transaksis' => $filteredTransaksis
        ]);
    }

}
