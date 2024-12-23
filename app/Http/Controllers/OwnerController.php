<?php

namespace App\Http\Controllers;

use App\Repositories\BarangRepository;
use App\Repositories\DepartemenRepository;
use App\Repositories\TransaksiRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class OwnerController extends Controller
{
    protected $transaksiRepository;
    protected $barangRepository;
    protected $departemenRepository;

    public function __construct(TransaksiRepository $transaksiRepository, BarangRepository $barangRepository, DepartemenRepository $departemenRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
        $this->barangRepository = $barangRepository;
        $this->departemenRepository = $departemenRepository;
    }

    public function ownerIndex()
    {
        $transaksis = $this->transaksiRepository->getAllTransaksis(); // Assuming you have this method
        $barangs = $this->barangRepository->getAllBarangs();
        $departements = $this->departemenRepository->getAllDepartements();

        return Inertia::render('Owner/Dashboard', [
            'transaksis' => $transaksis,
            'barangs' => $barangs,
            'departements' => $departements,
        ]);
    }

    public function ownerAcceptPengajuan(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required|string',
            'keterangan_approved' => 'nullable|string',
            'estimasi_tanggal' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the transaction by unique_id
        $transaksi = $this->transaksiRepository->findByUniqueId($request->unique_id); // Assuming you have this method

        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update the transaction status and other fields
        $transaksi->status = 'accepted'; // Update status as needed
        $transaksi->keterangan_approved = $request->keterangan_approved;
        $transaksi->estimasi_tanggal = $request->estimasi_tanggal;
        $transaksi->save();

        return response()->json(['message' => 'Transaction accepted successfully', 'transaksi' => $transaksi], 200);
    }
}
