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
        ->leftJoin('barangs', 'transaksi.barang_id', '=', 'barangs.id') 
        ->select('transaksi.id', 'barangs.namabarang as name', 'transaksi.created_at as date')
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