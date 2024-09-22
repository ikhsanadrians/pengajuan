<?php

namespace App\Repositories\Interfaces;

interface TransaksiRepositoryInterface
{
    public function getAllTransaksis();
    public function getTransaksiById($id);
    public function createTransaksi(array $data);
    public function updateTransaksi($id, array $data);
    public function deleteTransaksi($id);
}

?>