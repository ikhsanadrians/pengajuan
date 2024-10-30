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
            ->leftjoin('status as sts', 'sts.id' ,'=','pb.status_id')
            ->where('pb.user_id', $loginedUser)
            ->where('pb.statusenabled', '=', '1')
            ->orderBy('pb.created_at', 'desc')
            ->get();


        $pengajuanBarang->transform(function ($item) {
            $item->created_at = formatTanggalWithDayAndTime($item->created_at);
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

        if ($pengajuan) {
            $pengajuan->created_at = formatTanggalWithDayAndTime($pengajuan->created_at);

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
            ->leftJoin('status as sts', 'sts.id', '=', 'pb.status_id')
            ->where('pb.user_id', $loginedUser)
            ->where('pb.statusenabled', '=', '1');

        // Filter by date range
        if ($startDate && $endDate) {
            $query->whereBetween('pb.created_at', [$startDate, $endDate]);
        }

        // Filter by status
        if ($status) {
            $query->where('sts.nameexternal', '=', $status);
        }

        // Filter by search query in unique_id
        if ($searchQuery) {
            $query->where('pb.unique_id', 'like', '%' . $searchQuery . '%');
        }

        $pengajuanBarang = $query->orderBy('pb.created_at', 'desc')->get();

        $pengajuanBarang->transform(function ($item) {
            $item->created_at = formatTanggalWithDayAndTime($item->created_at);
            return $item;
        });

        return $pengajuanBarang;
    }
}

?>
