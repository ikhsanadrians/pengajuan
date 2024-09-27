<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;


class BarangRepository
{
    public function getAllBarangs()
    {
        return DB::table('barang')->get();
    }

    public function getBarangById($id)
    {
        return DB::table('barang')->where('id', $id)->first();
    }

    public function searchBarang($keyword)
    {
        return DB::table('barang')->where('nama', 'like', '%'.$keyword.'%')->get();
    }

    public function createBarang(array $data)
    {
        return DB::table('barang')->insert($data);
    }

    public function updateBarang($id, array $data)
    {
        return DB::table('barang')->where('id', $id)->update($data);
    }

    public function deleteBarang($id)
    {
        return DB::table('barang')->where('id', $id)->delete();
    }
}

?>