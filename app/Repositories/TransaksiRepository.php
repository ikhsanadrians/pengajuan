<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TransaksiRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TransaksiRepository implements TransaksiRepositoryInterface
{
    public function getAllTransaksis()
    {
        return DB::table('transaksi')->get();
    }

    public function getTransaksiById($id)
    {
        return DB::table('transaksi')->where('id', $id)->first();
    }

    public function createTransaksi(array $data)
    {
        return DB::table('transaksi')->insert($data);
    }

    public function updateTransaksi($id, array $data)
    {
        return DB::table('transaksi')->where('id', $id)->update($data);
    }

    public function deleteTransaksi($id)
    {
        return DB::table('transaksi')->where('id', $id)->delete();
    }
}