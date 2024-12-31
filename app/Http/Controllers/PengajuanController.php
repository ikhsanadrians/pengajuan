<?php

namespace App\Http\Controllers;

use App\Repositories\TransaksiRepository;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\PengajuanBarangRepositoryInterface;
use App\Repositories\PengajuanRepository;
use App\Repositories\BarangRepository;
use App\Events\NotificationEvent;


class PengajuanController extends Controller
{


    public function __construct(PengajuanRepository $pengajuanRepository, TransaksiRepository $transaksiRepository, BarangRepository $barangRepository)
    {
         $this->pengajuanRepository = $pengajuanRepository;
         $this->transaksiRepository = $transaksiRepository;
         $this->barangRepository = $barangRepository;
    }

    public function index()
    {
        $data = $this->pengajuanBarangRepository->getAllPengajuanBarangs();
        return view('pengajuan.index', compact('data'));
    }



    public function simpanPengajuanUser(Request $request)
    {
        try {
            $departemen_id = $request->input('departement');
            $keterangan = $request->input('keterangan');
            $user_id = auth()->user()->id;
            $unique_id = generateUniqueId($departemen_id);
            $status_id = 1;

            $images = $request->file('images') ?? [];

            $barangGroups = json_decode($request->input('barangDiajukan'), true);

            if (!is_array($barangGroups)) {
                throw new \Exception('Invalid barang data');
            }

            $totalQuantity = 0;
            $processedBarangGroups = [];

            foreach ($barangGroups as $index => $barangGroup) {
                $totalQuantity += $barangGroup['quantity'];

                if (!empty($barangGroup['nama_manual'])) {
                    $barangData = [
                        'namabarang' => $barangGroup['nama_manual'],
                        'quantity' => 100,
                        'category_id' => 1,
                        'satuan' => 'pcs',
                        'created_at' => now(),
                        'updated_at' => now(),
                        'statusenabled' => true,
                    ];

                    $barang_id = $this->barangRepository->createBarang($barangData);
                    $barangGroup['barang_id'] = $barang_id;
                }

                if (!empty($barangGroup['has_image']) && $barangGroup['has_image']) {
                    $imageIndex = $barangGroup['image_index'];

                    if (isset($images[$imageIndex]) && $images[$imageIndex]->isValid()) {
                        $path = $images[$imageIndex]->store('public/images');
                        $barangGroup['gambar_pendukung'] = 'storage/' . substr($path, 7);
                    }
                }

                $processedBarangGroups[] = $barangGroup;
            }

            $pengajuanData = [
                'unique_id' => $unique_id,
                'user_id' => $user_id,
                'statusenabled' => true,
                'departemen_id' => $departemen_id,
                'quantity' => $totalQuantity,
                'keterangan' => $keterangan,
                'estimasi' => null,
                'status_id' => $status_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $pengajuan_id = $this->pengajuanRepository->createPengajuanBarang($pengajuanData);

            foreach ($processedBarangGroups as $index => $barangGroup) {
                if (!isset($barangGroup['barang_id']) || !$barangGroup['barang_id']) {
                    continue;
                }

                $transactionData = [
                    'pengajuan_id' => $pengajuan_id,
                    'barang_id' => $barangGroup['barang_id'],
                    'quantity' => $barangGroup['quantity'],
                    'keterangan' => $barangGroup['keterangan'],
                    'status_id' => $barangGroup['status_id'],
                    'satuan_id' => $barangGroup['satuan_id'],
                    'harga_satuan' => $barangGroup['harga_satuan'],
                    'harga_total' => $barangGroup['harga_total']
                ];

                if (isset($barangGroup['gambar_pendukung'])) {
                    $transactionData['gambar_pendukung'] = $barangGroup['gambar_pendukung'];
                }

                $transaction = $this->transaksiRepository->createTransaksi($transactionData);
            }

            return redirect()->route('user');

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }




   public function getDetailPengajuan(Request $request){
        $currentPengajuanId = $request->pengajuanId;

        $pengajuanDetail = $this->pengajuanRepository->getPengajuanDetailByCode($currentPengajuanId);

        return response()->json([
            "message" => "Success, Get Pengajuan Detail",
            "data" => $pengajuanDetail
        ],200);
   }

   public function deletePengajuan(Request $request) {
        $pengajuanId = $request->pengajuanId;

        $this->pengajuanRepository->deletePengajuanBarang($pengajuanId);

        return response()->json([
            "message" => "Success, Pengajuan Deleted",
            "data" => null
        ], 200);
   }


   public function rejectPengajuan(Request $request){
        $pengajuanId = $request->pengajuanId;
        $keteranganRejected = $request->keteranganRejected;

        $this->pengajuanRepository->RejectPengajuan($pengajuanId, $keteranganRejected);

        return response()->json([
            "message" => "Success, Pengajuan Rejected",
            "payload" => true,
            "data" => [$pengajuanId, $keteranganRejected]
        ], 200);
    }





}