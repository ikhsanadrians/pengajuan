<?php

namespace App\Repositories\Interfaces;

interface DepartementsRepositoryInterface
{
    public function getAllDepartements();
    public function getDepartementById($id);
    public function createDepartement(array $data);
    public function updateDepartement($id, array $data);
    public function deleteDepartement($id);
}

?>