<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\BarangRepository;
use App\Repositories\KategoriRepository;
use Illuminate\Support\Carbon;

class BarangController extends Controller
{
    protected $barangRepository;
    protected $kategoriRepository;

    public function __construct(
        BarangRepository $barangRepository,
        KategoriRepository $kategoriRepository
    ) {
        $this->barangRepository = $barangRepository;
        $this->kategoriRepository = $kategoriRepository;
    }

    public function adminBarangsIndex()
    {
        $barangs = $this->barangRepository->getAllBarangs();
        $categories = $this->kategoriRepository->getAllKategoris();
        return Inertia::render('Admin/AddBarang', [
            'barangs' => $barangs,
            'categories' => $categories
        ]);
    }

    public function adminBarangsShow(string $id)
    {
        $barang = $this->barangRepository->getBarangById($id);

        if (!$barang) {
            return response()->json([
                "message" => "Barang Tidak Ditemukan"
            ]);
        }

        return response()->json([
            "message" => "Barang Ditemukan",
            "data" => $barang
        ]);
    }

    public function adminBarangsAdd(Request $request)
    {
        $request->validate([
            'namabarang' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|integer',
            'satuan' => 'required|string|max:255',
        ]);

        $data = [
            'namabarang' => $request->namabarang,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'satuan' => $request->satuan,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        try {
            $this->barangRepository->createBarang($data);

            return redirect()->route('admin.BarangsIndex')->with('success', 'Barang baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getLine(),
                'detail' => $e->getMessage()
            ], 500);
        }
    }

    public function adminBarangsUpdate(string $id, Request $request)
    {
        try {
            $data = $request->all();
            $updated = $this->barangRepository->updateBarang($id, $data);

            if (!$updated) {
                return response()->json([
                    'message' => 'Failed to update Barang data.',
                ], 400);
            }

            return redirect()->route('admin.BarangsIndex')->with('success', 'Barang berhasil diperbarui!');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating Barang data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function adminBarangsDelete(Request $request)
    {
        try {
            $idToDelete = $request->id;
            $deleteProceed = $this->barangRepository->deleteBarang($idToDelete);

            if ($deleteProceed) {
                return redirect()->route('admin.BarangsIndex')->with('success', 'Barang berhasil dihapus!');
            } else {
                return response()->json([
                    "message" => "Error, Gagal Menghapus Barang",
                    "data" => $deleteProceed,
                    "Request" => $request->id
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error, Terjadi kesalahan sistem. Mohon coba lagi nanti.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}