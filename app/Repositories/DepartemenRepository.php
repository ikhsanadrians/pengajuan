<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\DepartementsRepositoryInterface;

class DepartemenRepository implements DepartementsRepositoryInterface
{
    public function getAllDepartements()
    {
        return DB::table('departements')->get();
    }

    public function getDepartementById($id)
    {
        return DB::table('departements')->where('id', $id)->first();
    }

    public function createDepartement(array $data)
    {
        return DB::table('departements')->insert($data);
    }

    public function updateDepartement($id, array $data)
    {
        return DB::table('departements')->where('id', $id)->update($data);
    }

    public function deleteDepartement($id)
    {
        return DB::table('departements')->where('id', $id)->delete();
    }
}

?>