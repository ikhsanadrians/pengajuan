<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;

class TransaksiController extends Controller
{
    protected $transaksiRepository;

    public function __construct(TransaksiRepository $transaksiRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
    }
    public function rejectTransaksi(Request $request){

        $transaksiId = $request->input('transaksi_id');
        $keterangan = $request->input('keterangan');


        $response = $this->transaksiRepository->rejectTransaksi($transaksiId, $keterangan);

        if (!$response) {
            return response()->json(['message' => 'Gagal Memperbarui Transaksi'], 401);
        }

        return response()->json(['message' => 'Transaksi successfully rejected', 'data' => $response], 200);

    }
}