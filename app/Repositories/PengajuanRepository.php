<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\PengajuanBarangRepositoryInterface;

class PengajuanRepository
{
    public function getAllPengajuanBarangs()
    {
        return DB::table('pengajuan_barang')->get();
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
        return DB::table('pengajuan_barang')->insert($data);
    }

    public function deletePengajuanBarang($id)
    {
        return DB::table('pengajuan_barang')->where('id', $id)->delete();
    }
}

?>