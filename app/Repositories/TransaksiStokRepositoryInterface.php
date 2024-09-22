<?php

namespace App\Repositories;

interface TransaksiStokRepositoryInterface
{
    public function getAllTransaksiStoks();
    public function getTransaksiStokById($id);
    public function createTransaksiStok($transactionType,array $data);
    public function updateTransaksi($id, array $data);
    public function deleteTransaksi($id);
}

?>