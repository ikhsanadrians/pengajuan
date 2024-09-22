<?php

namespace App\Repositories\Interfaces;

interface BarangRepositoryInterface
{
    public function getAllBarangs();
    public function getBarangById($id);
    public function searchBarang($keyword);
    public function createBarang(array $data);
    public function updateBarang($id, array $data);
    public function deleteBarang($id);
}





?>