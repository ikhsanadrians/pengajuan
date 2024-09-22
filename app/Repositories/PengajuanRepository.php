<?php 

namespace App\Repositories;

use App\Repositories\Interfaces\PengajuanBarangRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PengajuanRepository implements PengajuanBarangRepositoryInterface
{
    public function getAllPengajuan()
    {
        return DB::table('pengajuan')->get();
    }

    public function getPengajuanById($id)
    {
        return DB::table('pengajuan')->where('id', $id)->first();
    }

    public function approvePengajuan($id, $approveType, array $data)
    {
        return DB::table('pengajuan')->where('id', $id)->update(['status' => $approveType]);
    }

    public function createPengajuan(array $data)
    {
        return DB::table('pengajuan')->insert($data);
    }

    public function createTransaksi(array $data){
        return DB::table('transaksi')->insert($data);
    }


    public function updatePengajuan($id, array $data)
    {
        return DB::table('pengajuan')->where('id', $id)->update($data);
    }   

    public function deletePengajuan($id)
    {
        return DB::table('pengajuan')->where('id', $id)->delete();
    }
}           

