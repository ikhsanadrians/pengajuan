<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class KategoriRepository
{
    public function getAllKategoris()
    {
        return DB::table('kategori')->get();
    }

    public function getKategoriById($id)
    {
        return DB::table('kategori')->where('id', $id)->first();
    }

    public function createKategori(array $data)
    {
        return DB::table('kategori')->insert($data);
    }

    public function updateKategori($id, array $data)
    {
        return DB::table('kategori')->where('id', $id)->update($data);
    }

    public function deleteKategori($id)
    {
        return DB::table('kategori')->where('id', $id)->delete();
    }
}