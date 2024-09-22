<?php

namespace App\Repositories;

interface BarangRepositoryInterface
{
    public function getAllBarangs();
    public function getBarangById($id);
    public function createBarang(array $data);
    public function updateBarang($id, array $data);
    public function deleteBarang($id);
}





?>