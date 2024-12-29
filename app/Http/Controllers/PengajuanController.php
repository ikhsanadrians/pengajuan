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

    public function handleImageUpload($image)
    {
        if ($image instanceof \Illuminate\Http\UploadedFile) {
            // Jika gambar adalah file yang di-upload, simpan gambar menggunakan Laravel's Storage
            return $image->store('public/images'); // Menyimpan gambar di folder public/images
        } elseif (is_string($image) && preg_match('/^data:image/', $image)) {
            // Jika gambar berupa base64, konversikan menjadi file
            $imageData = base64_decode(preg_replace('/^data:image\/(jpeg|png|jpg|gif);base64,/', '', $image));
            $fileName = uniqid('image_') . '.jpg'; // Nama file unik
            $path = storage_path('app/public/images/' . $fileName);

            file_put_contents($path, $imageData);

            return 'storage/images/' . $fileName; // Path relatif untuk akses file
        }

        return null; // Jika tidak ada gambar yang valid
    }


    public function simpanPengajuanUser(Request $request)
    {
        $departemen_id = $request->input('departement');
        $keterangan = $request->input('keterangan');
        $user_id = auth()->user()->id;
        $unique_id = generateUniqueId($departemen_id);
        $status_id = 1;
        $created_at = now();
        $updated_at = now();

        // Menangani barang yang diajukan
        $barangGroups = json_decode($request->input('barangDiajukan'), true);

        if (is_array($barangGroups)) {
            $totalQuantity = 0;

            // Proses total quantity barang
            foreach ($barangGroups as $index => &$barangGroup) {
                if (isset($barangGroup['quantity'])) {
                    $totalQuantity += $barangGroup['quantity'];
                }

                // Jika barang adalah barang manual, buat entri barang baru
                if (isset($barangGroup['nama_manual']) && $barangGroup['nama_manual']) {
                    $barangData = [
                        'namabarang' => $barangGroup['nama_manual'],
                        'quantity'   => 100, // Contoh default quantity, bisa disesuaikan
                        'category_id'=> 1,
                        'satuan'     => 'pcs', // Satuan default, sesuaikan jika perlu
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'statusenabled' => true,
                    ];

                    // Simpan barang baru dan ambil ID barang
                    $barang_id = $this->barangRepository->createBarang($barangData);
                    $barangGroup['barang_id'] = $barang_id;
                    unset($barangGroup['nama_manual']); // Hapus nama manual setelah digunakan
                }

                // Jika ada gambar yang diupload, proses gambar tersebut
                if (isset($barangGroup['gambar_pendukung'])) {
                    // Cek apakah gambar ada di request dan apakah itu file
                    if ($request->hasFile("barangDiajukan.{$index}.gambar_pendukung")) {
                        $gambarFile = $request->file("barangDiajukan.{$index}.gambar_pendukung");

                        if ($gambarFile && $gambarFile->isValid()) {
                            // Simpan gambar dan ambil path-nya
                            $gambarPath = $gambarFile->store('public/images');
                            $barangGroup['gambar_pendukung'] = 'storage/images/' . basename($gambarPath);
                        }
                    }
                }
            }

            // Membuat data pengajuan
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

            // Simpan pengajuan barang dan dapatkan ID pengajuan
            $pengajuan_id = $this->pengajuanRepository->createPengajuanBarang($pengajuanData);

            // Proses setiap barang yang diajukan
            foreach ($barangGroups as &$barangGroup) {
                // Menambahkan barang yang telah diproses ke transaksi
                $barangGroup['pengajuan_id'] = $pengajuan_id;
                $this->transaksiRepository->createTransaksi($barangGroup);
            }

            return redirect()->route('user');
        }

        return response()->json(['message' => 'Data barang tidak valid.'], 400);
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