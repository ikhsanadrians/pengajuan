<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\KategoriRepository;
use Illuminate\Support\Carbon;

class KategoriController extends Controller
{
    protected $kategoriRepository;

    public function __construct(KategoriRepository $kategoriRepository)
    {
        $this->kategoriRepository = $kategoriRepository;
    }

    public function adminKategoriIndex()
    {
        $kategoris = $this->kategoriRepository->getAllKategoris();

        return Inertia::render('Admin/AddKategori', ['kategoris' => $kategoris]);
    }

    public function adminKategoriShow(string $id)
    {
        $kategori = $this->kategoriRepository->getKategoriById($id);

        if (!$kategori) {
            return response()->json([
                "message" => "Kategori Tidak Ditemukan"
            ]);
        }

        return response()->json([
            "message" => "Kategori Ditemukan",
            "data" => $kategori
        ]);
    }

    public function adminKategoriAdd(Request $request)
    {
        $request->validate([
            'nameexternal' => 'required|string|max:255',
        ]);

        $data = [
            'nameexternal' => $request->nameexternal,
            'statusenabled' => true, // default to true
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        try {
            $this->kategoriRepository->createKategori($data);

            return redirect()->route('admin.KategoriIndex')->with('success', 'Kategori baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getLine(),
                'detail' => $e->getMessage()
            ], 500);
        }
    }

    public function adminKategoriUpdate(string $id, Request $request)
    {
        try {
            $data = $request->all();
            $updated = $this->kategoriRepository->updateKategori($id, $data);

            if (!$updated) {
                return response()->json([
                    'message' => 'Failed to update Kategori data.',
                ], 400);
            }

            return redirect()->route('admin.KategoriIndex')->with('success', 'Kategori berhasil diperbarui!');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating Kategori data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function adminKategoriDelete(Request $request)
    {
        try {
            $idToDelete = $request->kategoriIdToDelete;
            $deleteProceed = $this->kategoriRepository->deleteKategori($idToDelete);

            if ($deleteProceed) {
                return redirect()->route('admin.KategoriIndex')->with('success', 'Kategori berhasil dihapus!');
            } else {
                return response()->json([
                    "message" => "Error, Gagal Menghapus Kategori",
                    "data" => $deleteProceed,
                    "Request" => $request->idToDelete
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