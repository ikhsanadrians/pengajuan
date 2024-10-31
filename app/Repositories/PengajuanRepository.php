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
           ->select('pb.*', 'sts.nameexternal') 
           ->leftJoin('status as sts', 'sts.id', '=', 'pb.status_id')
           ->where('pb.user_id', $loginedUser)
           ->where('pb.statusenabled', '=', '1')
           ->orderBy('pb.created_at', 'desc')
           ->get();

         $pengajuanBarang->transform(function ($item) {
                 try {
                  if ($item->created_at) {
                         $item->created_at = formatTanggalWithDayAndTime($item->created_at);
                     } else {
    
                         $item->created_at = 'Tanggal tidak tersedia';
                     }
                 } catch (\Exception $e) {
            
                     \Log::error("Error formatting date: " . $e->getMessage());
                     $item->created_at = 'Format tanggal gagal';
                 }
                 return $item;
             });
        
        return $pengajuanBarang;
    }

    public function getPengajuanBarangById($id)
    {
        return DB::table('pengajuan_barang')->where('id', $id)->first();
    }
    public function getPengajuanDetailByCode($uniqueCode){
        $pengajuan = DB::table('pengajuanbarang as pb')
            ->leftJoin('status as sts', 'sts.id', '=', 'pb.status_id')
            ->leftJoin('departements as d', 'd.id', '=', 'pb.departemen_id')
            ->where('pb.unique_id', '=', $uniqueCode)
            ->select(
        'pb.*',
                 'sts.nameexternal as status_name',
                 'd.namadepartemen as namadepartement'
            )
            ->first();
            
            $pengajuan->created_at = formatTanggalWithDayAndTime($pengajuan->created_at);
        
            if ($pengajuan) {

            $transaksi = DB::table('transaksi as tr')
                ->where('tr.pengajuan_id', '=', $pengajuan->id)
                ->leftJoin('barangs as b', 'b.id', '=', 'tr.barang_id')
                ->leftJoin('status as s', 's.id' , 'tr.status_id')
                ->select(
                    'tr.*',
                    'b.namabarang',
                    's.nameexternal as status'
                )
                ->get();

            $pengajuan->transaksi = $transaksi;
        }

        return $pengajuan;
    }

    public function approvePengajuanBarang($id, $approveType, array $data)
    {
        return DB::table('pengajuan_barang')->where('id', $id)->update(array_merge(['status' => $approveType], $data));
    }

    public function createPengajuanBarang(array $data)
    {
        return DB::table('pengajuanbarang')->insertGetId($data);
    }
    public function deletePengajuanBarang($id): bool
    {
        $pengajuan = DB::table('pengajuanbarang')->where('unique_id', $id)->first();

        if ($pengajuan) {
            DB::beginTransaction();
            try {
                DB::table('transaksi')->where('pengajuan_id', $pengajuan->id)->delete();
                DB::table('pengajuanbarang')->where('unique_id', $id)->delete();
                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollback();
                return false;
            }
        }

        return false;
    }

    public function getFilteredPengajuanBarangs($startDate = null, $endDate = null, $status = null, $searchQuery = null)
    {
        $loginedUser = Auth::user()->id ?? null;
    
        $query = DB::table('pengajuanbarang as pb')
            ->select('pb.*', 'sts.nameexternal') // Spesifik kolom untuk menghindari konflik
            ->leftJoin('status as sts', 'sts.id', '=', 'pb.status_id')
            ->where('pb.user_id', $loginedUser)
            ->where('pb.statusenabled', '=', '1');
           
            if ($startDate && $endDate) {
                // Set waktu start ke awal hari (00:00:00) dan end ke akhir hari (23:59:59)
                $query->whereBetween('pb.created_at', [
                    $startDate . ' 00:00:00', 
                    $endDate . ' 23:59:59'
                ]);
            } elseif ($startDate) {
                // Untuk single date, gunakan whereBetween juga dengan start dan end di hari yang sama
                $query->whereBetween('pb.created_at', [
                    $startDate . ' 00:00:00',
                    $startDate . ' 23:59:59'
                ]);
            } elseif ($endDate) {
                // Sama seperti di atas
                $query->whereBetween('pb.created_at', [
                    $endDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            }

        if ($status) {
            $query->where('sts.nameexternal', '=', $status);
        }
    
        if ($searchQuery) {
            $query->where('pb.unique_id', 'like', '%' . $searchQuery . '%');
        }
    
        $pengajuanBarang = $query->orderBy('pb.created_at', 'desc')->get();
    
        // Transform tanggal hanya jika nilai tidak null
        $pengajuanBarang->transform(function ($item) {
            if ($item->created_at) {
                $item->created_at = formatTanggalWithDayAndTime($item->created_at);
            } else {
                $item->created_at = 'Tanggal tidak tersedia';
            }
            return $item;
        });
    
        return $pengajuanBarang;
    }
    
}

?>
