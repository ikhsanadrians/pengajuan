<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\PengajuanBarangRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    public function getAllPengajuanBarangsAdmin()
    {

        $pengajuanBarang = DB::table('pengajuanbarang as pb')
           ->select('pb.*', 'sts.nameexternal', 'usr.username')
           ->leftJoin('status as sts', 'sts.id', '=', 'pb.status_id')
           ->leftJoin('users as usr', 'usr.id' , '=' , 'pb.user_id')
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

    public function getAllPengajuanBarangsOwner()
    {

        $pengajuanBarang = DB::table('pengajuanbarang as pb')
           ->select('pb.*', 'sts.nameexternal', 'usr.username')
           ->leftJoin('status as sts', 'sts.id', '=', 'pb.status_id')
           ->leftJoin('users as usr', 'usr.id' , '=' , 'pb.user_id')
           ->where('pb.statusenabled', '=', '1')
           ->where('pb.status_id', '!=', '1')
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
            ->leftJoin('users as usr', 'usr.id', '=' , 'pb.user_id')
            ->where('pb.unique_id', '=', $uniqueCode)
            ->select(
        'pb.*',
                 'sts.nameexternal as status_name',
                 'd.namadepartemen as namadepartement',
                 'usr.username as user'
            )
            ->first();

            $pengajuan->created_at = formatTanggalWithDayAndTime($pengajuan->created_at);

            if ($pengajuan) {

            $transaksi = DB::table('transaksi as tr')
                ->where('tr.pengajuan_id', '=', $pengajuan->id)
                ->leftJoin('barangs as b', 'b.id', '=', 'tr.barang_id')
                ->leftJoin('status as s', 's.id' , 'tr.status_id')
                ->leftJoin('satuan as stn', 'stn.id' , '=', 'tr.satuan_id')
                ->select(
                    'tr.*',
                    'b.namabarang',
                    's.nameexternal as status',
                    'stn.namasatuan as satuan'
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
            $query->where('sts.id', '=', $status);
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

    public function getFilteredPengajuanBarangsAdmin($startDate = null, $endDate = null, $status = null, $searchQuery = null)
    {
        $loginedUser = Auth::user()->id ?? null;

        $query = DB::table('pengajuanbarang as pb')
            ->select('pb.*', 'sts.nameexternal') // Spesifik kolom untuk menghindari konflik
            ->leftJoin('status as sts', 'sts.id', '=', 'pb.status_id')
            ->leftJoin('users as usr', 'usr.id' , '=' , 'pb.user_id')
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
            $query->where('sts.id', '=', $status);
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


    public function getFilteredPengajuanBarangsOwner($startDate = null, $endDate = null, $status = null, $searchQuery = null)
    {
        $loginedUser = Auth::user()->id ?? null;
        $query = DB::table('pengajuanbarang as pb')
            ->select('pb.*', 'sts.nameexternal') // Spesifik kolom untuk menghindari konflik
            ->leftJoin('status as sts', 'sts.id', '=', 'pb.status_id')
            ->leftJoin('users as usr', 'usr.id' , '=' , 'pb.user_id')
            ->where('pb.statusenabled', '=', '1')
            ->where('pb.status_id', '!=', '4')
            ->where('pb.status_id', '!=', '1');

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
            $query->where('sts.id', '=', $status);
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


    public function simpanVerifPengajuan($pengajuanId, $keterangan = null, $estimasi = null)
{
    try {
        // Mulai transaksi database
        DB::beginTransaction();

        // Update tabel pengajuanbarang
           $pengajuan = DB::table('pengajuanbarang')
            ->where('unique_id', $pengajuanId)
            ->first();

            $affectedRowsPengajuan = DB::table('pengajuanbarang')
            ->where('id', $pengajuan->id)
            ->update([
                'status_id' => 2,
                'keterangan_approved' => $keterangan,
                'estimasi' => $estimasi,
                'updated_at' => now()
            ]);

        // Update tabel transaksi, tetapi hanya yang bukan status_id 4
        $affectedRowsTransaksi = DB::table('transaksi')
            ->where('pengajuan_id', $pengajuan->id)
            ->where('status_id', '!=', 4) // Pastikan hanya update jika status_id bukan 4
            ->update([
                'status_id' => 2,
                'updated_at' => now()
            ]);

        // Commit transaksi jika semua operasi berhasil
        DB::commit();

        return [
            'affectedRowsPengajuan' => $affectedRowsPengajuan,
            'affectedRowsTransaksi' => $affectedRowsTransaksi
        ];
    } catch (\Exception $e) {
        // Rollback transaksi jika ada kesalahan
        DB::rollBack();

        \Log::error('Error updating pengajuan or transaksi: ' . $e->getMessage());
        throw $e; // Lempar error agar bisa ditangani oleh controller
    }
}


public function approvePengajuan($pengajuanId)
{
    try {
        // Mulai transaksi database
        DB::beginTransaction();

        // Update tabel pengajuanbarang
        $pengajuan = DB::table('pengajuanbarang')
            ->where('unique_id', $pengajuanId)
            ->first();

        $affectedRowsPengajuan = DB::table('pengajuanbarang')
            ->where('id', $pengajuan->id)
            ->update([
                'status_id' => 3, // Set status_id to 3 for approved
                'updated_at' => now()
            ]);

        // Update tabel transaksi, tetapi hanya yang bukan status_id 4
        $affectedRowsTransaksi = DB::table('transaksi')
            ->where('pengajuan_id', $pengajuan->id)
            ->where('status_id', '!=', 4) // Pastikan hanya update jika status_id bukan 4
            ->update([
                'status_id' => 3, // Set status_id to 3 for approved
                'updated_at' => now()
            ]);

        // Commit transaksi jika semua operasi berhasil
        DB::commit();

        return [
            'affectedRowsPengajuan' => $affectedRowsPengajuan,
            'affectedRowsTransaksi' => $affectedRowsTransaksi
        ];
    } catch (\Exception $e) {
        // Rollback transaksi jika ada kesalahan
        DB::rollBack();

        \Log::error('Error approving pengajuan: ' . $e->getMessage());
        throw $e; // Lempar error agar bisa ditangani oleh controller
    }
}


public function RejectPengajuan($id, $keterangan){
    try {
        // Mulai transaksi database
        DB::beginTransaction();

        // Update pengajuanbarang status to rejected
        $affectedRowsPengajuan = DB::table('pengajuanbarang')->where('unique_id', $id)->update([
            'status_id' => 4,
            'keterangan_rejected' => $keterangan,
            'updated_at' => Carbon::now()
        ]);

        // Update transaksi status to rejected for the corresponding pengajuan
        $affectedRowsTransaksi = DB::table('transaksi')
            ->where('pengajuan_id', function($query) use ($id) {
                $query->select('id')->from('pengajuanbarang')->where('unique_id', $id);
            })
            ->update([
                'status_id' => 4,
                'updated_at' => now()
            ]);

        // Commit transaksi jika semua operasi berhasil
        DB::commit();

        return [
            'affectedRowsPengajuan' => $affectedRowsPengajuan,
            'affectedRowsTransaksi' => $affectedRowsTransaksi
        ];
    } catch (\Exception $e) {
        // Rollback transaksi jika ada kesalahan
        DB::rollBack();

        \Log::error('Error rejecting pengajuan: ' . $e->getMessage());
        throw $e; // Lempar error agar bisa ditangani oleh controller
    }
}


}

?>
