<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TransaksiRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TransaksiRepository
{
    public function getAllTransaksis()
    {
        return DB::table('transaksi')->get();
    }

    public function getAllTransaksisAdmin(){

        return DB::table('transaksi')
        ->join('status', 'transaksi.status_id', '=', 'status.id')
        ->leftJoin('barangs', 'transaksi.barang_id', '=', 'barangs.id') // Menggunakan tabel 'barangs'
        ->select('transaksi.id', 'barangs.namabarang as name', 'status.nameexternal as status', 'transaksi.created_at as date') // 'namabarang' untuk nama barang, 'nameexternal' untuk nama status
        ->where('transaksi.status_id', '=', 1)
        ->get();

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