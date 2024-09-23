<?php

namespace App\Repositories\Interfaces;

interface TransaksiStokRepositoryInterface
{
    public function getAllTransaksiStoks();
    public function getTransaksiStokById($id);
    public function createTransaksiStok($transactionType,array $data);
    public function updateTransaksiStok($id, array $data);
    public function deleteTransaksiStok($id);
}

?>