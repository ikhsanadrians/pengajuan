<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\PengajuanBarangRepositoryInterface;
use App\Repositories\PengajuanRepository;

class PengajuanController extends Controller
{
    protected $pengajuanRepo;

    public function __construct($pengajuanRepository)
    {
      $pengajuanBarangRepository = $this->pengajuanBarangRepository;
    }

    public function index()
    {
        $data = $this->pengajuanBarangRepository->getAllPengajuanBarangs();
        return view('pengajuan.index', compact('data'));
    }
}
