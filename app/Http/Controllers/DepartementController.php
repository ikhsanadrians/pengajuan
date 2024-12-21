<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\BarangRepository;
use App\Repositories\DepartemenRepository;
use App\Repositories\TransaksiRepository;
use App\Repositories\PengajuanRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use Illuminate\Support\Carbon;

class DepartementController extends Controller
{
    public function __construct(TransaksiRepository $transaksiRepository, BarangRepository $barangRepository, DepartemenRepository $departemenRepository, PengajuanRepository $pengajuanRepository, UserRepository $userRepository
    )
    {
        $this->transaksiRepository = $transaksiRepository;
        $this->barangRepository = $barangRepository;
        $this->departemenRepository = $departemenRepository;
        $this->pengajuanRepository = $pengajuanRepository;
        $this->userRepository = $userRepository;
    }


    public function adminDepartementIndex(){
          $departements = $this->departemenRepository->getAllDepartements();


          return Inertia::render('Admin/AddDepartement', ['departementsUser' => $departements]);
    }


    public function adminDepartementShow(string $id){
         $perDepartement = $this->departemenRepository->getDepartementById($id);

         if(!$perDepartement){
              return response()->json([
                "message" => "Departement Tidak Ditemukan"
              ]); 
         }

         return response()->json([
            "message" => "Departement Ditemukan",
            "data" => $perDepartement
         ]);
    }


    public function adminDepartementAdd(Request $request){
        $request->validate([
            'namadepartemen' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $data = [
            'namadepartemen' => $request->namadepartemen,
            'address' => $request->address,
            'statusenabled' => true, // default to true
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        try {
            $this->departemenRepository->createDepartement($data);

            return redirect()->route('admin.DepartementIndex')->with('success', 'Departement baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getLine(),
                'detail' => $e->getMessage()
            ], 500);
        }    }

    
}
