<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\PengajuanBarangRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class PengajuanRepository
{
    public function getAllPengajuanBarangs()
    {
        return DB::table('pengajuan_barang')->get();
    }

    public function getAllPengajuanBarangsUser()
    {

        $loginedUser = Auth::user()->id ?? null;

        $pengajuanBarang = DB::table('pengajuanbarang as pb')
            ->leftjoin('status as sts', 'sts.id' ,'=','pb.status_id')
            ->where('pb.user_id', $loginedUser)
            ->where('pb.statusenabled', '=', '1')
            ->orderBy('pb.created_at', 'desc')
            ->get();


        $pengajuanBarang->transform(function ($item) {
            $item->created_at = formatTanggalWithDayAndTime($item->created_at);
            return $item;
        });

        return $pengajuanBarang;
    }

    public function getPengajuanBarangById($id)
    {
        return DB::table('pengajuan_barang')->where('id', $id)->first();
    }

    public function approvePengajuanBarang($id, $approveType, array $data)
    {
        return DB::table('pengajuan_barang')->where('id', $id)->update(array_merge(['status' => $approveType], $data));
    }

    public function createPengajuanBarang(array $data)
    {
        return DB::table('pengajuanbarang')->insertGetId($data);
    }

    public function deletePengajuanBarang($id)
    {
        return DB::table('pengajuan_barang')->where('id', $id)->delete();
    }
}

?>