<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TransaksiRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Use Carbon to handle dates

class TransaksiRepository
{
    public function getAllTransaksisUser()
    {
        $loginedUser = Auth::user()->id ?? null;

        return DB::table('transaksi as tr')
        ->leftJoin('barangs', 'tr.barang_id', '=', 'barangs.id')
        ->leftJoin('pengajuanbarang as pb', 'pb.id' ,'=' ,'tr.pengajuan_id')
        ->where('pb.user_id', $loginedUser)
        ->select('tr.id', 'barangs.namabarang as name', 'tr.created_at as date', 'pb.*')
        ->get();


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
        \Log::info('Creating transaction with data:', $data);
        $data = array_merge($data, [
            'statusenabled' => true,
            'status_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $id = DB::table('transaksi')->insertGetId($data);
        \Log::info('Created transaction with ID:', ['id' => $id]);
        return $id;
    }



    public function updateTransaksi($id, array $data)
    {
        return DB::table('transaksi')->where('id', $id)->update($data);
    }

    public function deleteTransaksi($id)
    {
        return DB::table('transaksi')->where('id', $id)->delete();
    }

    public function rejectTransaksi(int $id, string $keterangan)
    {
        return DB::table('transaksi')->where('id', $id)->update([
            'statusenabled' => 0,
            'status_id' => 4,
            'keterangan_rejected' => $keterangan,
            'updated_at' => Carbon::now()
        ]);
    }

}