<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return DB::table('users')->get();
    }

    public function getUserById($id)
    {
        return DB::table('users')->where('id', $id)->first();
    }

    public function createUser(array $data)
    {
        return DB::table('users')->insert($data);
    }

    public function updateUser($id, array $data)
    {
        return DB::table('users')->where('id', $id)->update($data);
    }

    public function deleteUser($id)
    {
        return DB::table('users')->where('id', $id)->delete();
    }
}

?>