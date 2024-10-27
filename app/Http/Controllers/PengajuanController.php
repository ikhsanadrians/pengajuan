<?php

namespace App\Http\Controllers;

use App\Repositories\TransaksiRepository;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\PengajuanBarangRepositoryInterface;
use App\Repositories\PengajuanRepository;

class PengajuanController extends Controller
{


    public function __construct(PengajuanRepository $pengajuanRepository, TransaksiRepository $transaksiRepository)
    {
         $this->pengajuanRepository = $pengajuanRepository;
         $this->transaksiRepository = $transaksiRepository;
    }

    public function index()
    {
        $data = $this->pengajuanBarangRepository->getAllPengajuanBarangs();
        return view('pengajuan.index', compact('data'));
    }



    public function simpanPengajuanUser(Request $request){

    $departemen_id = $request->input('departement');
    $keterangan = $request->input('keterangan');

    $user_id = auth()->user()->id;
    $unique_id = generateUniqueId($departemen_id);

    $status_id = 1;
    $created_at = now();
    $updated_at = now();

    $barangGroups = $request->barangDiajukan;


    $totalQuantity = array_sum(array_column($barangGroups, 'quantity'));

    $pengajuanData = [
        'unique_id'     => $unique_id,
        'user_id'       => $user_id,
        'statusenabled' => true,
        'departemen_id' => $departemen_id,
        'quantity'      => $totalQuantity,
        'keterangan'    => $keterangan,
        'estimasi'      => null,
        'status_id'     => $status_id,
        'created_at'    => $created_at,
        'updated_at'    => $updated_at,
    ];

    $pengajuan_id = $this->pengajuanRepository->createPengajuanBarang($pengajuanData);



    foreach ($barangGroups as $barangGroup) {
        $barangGroup['pengajuan_id'] = $pengajuan_id;
        $this->transaksiRepository->createTransaksi($barangGroup);
    }

    return redirect()->route('user');
}


}