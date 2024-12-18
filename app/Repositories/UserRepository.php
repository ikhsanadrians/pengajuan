<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;


class UserRepository
{
    public function getAllUsers()
    {
        return DB::table('users as usr')->leftJoin('roles', 'usr.role_id', '=', 'roles.id')->select('usr.*', 'roles.namarole as role')->get();
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
        try {
            return DB::table('users')->where('id', $id)->delete();
        } catch (\Exception $e) {
            Log::error('Delete user failed', ['id' => $id, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    public function getAllRolesUser(){
        return DB::table('roles')->get(); 
    }

    
}

?>
