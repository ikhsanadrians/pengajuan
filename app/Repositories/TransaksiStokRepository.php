<?php

namespace App\Repositories;


use App\Repositories\Interfaces\TransaksiStokRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TransaksiStokRepository implements TransaksiStokRepositoryInterface
{
    public function getAllTransaksiStoks()
    {
         return DB::table('transaksi_stok')->get();
    }

    public function getTransaksiStokById($id)
    {
        return DB::table('transaksi_stok')->where('id', $id)->first();
    }

    public function createTransaksiStok($transactionType, array $data)
    {
        return DB::table('transaksi_stok')->insert($data);
    }

    public function updateTransaksiStok($id, array $data)
    {
        return DB::table('transaksi_stok')->where('id', $id)->update($data);
    }

    public function deleteTransaksiStok($id)
    {
        return DB::table('transaksi_stok')->where('id', $id)->delete();
    }
}

?>