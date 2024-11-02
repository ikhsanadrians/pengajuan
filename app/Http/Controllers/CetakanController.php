<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PengajuanRepository;


class CetakanController extends Controller
{

    public function __construct(PengajuanRepository $pengajuanRepository)
    {
         $this->pengajuanRepository = $pengajuanRepository;
    }

    public function cetakanDetailPengajuanUser(String $code){
         if(!$code){
            return redirect()->back();
         }

         $detailPengajuanDatas =  $this->pengajuanRepository->getPengajuanDetailByCode($code);

         return view('cetakan.user.detailpengajuan', compact('detailPengajuanDatas'));
    }
}