<?php

namespace App\Repositories;

interface PengajuanBarangRepositoryInterface
{
    public function getAllPengajuanBarangs();
    public function getPengajuanBarangById($id);
    public function createPengajuanBarang(array $data);
    public function approvePengajuanBarang($id, $approveType, array $data);
    public function deletePengajuanBarang($id);
}

?>