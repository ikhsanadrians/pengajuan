<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function user()
    {
        $userDatas = $this->userRepository->getAllUsers();


        return Inertia::render('');
    }

    public function storeUser(Request $request)
    {

        try {
            $data = [
                'namalengkap' => $request->full_name,
                'username' => $request->username,
                'role_id' => $request->role_id,
                'departement_id' => $request->departement_id,
                'password' => md5($request->password),
                'statusenabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $isCreated = $this->userRepository->createUser($data);

            if ($isCreated) {
                return redirect()->route('admin.userIndex')->with('success', 'User baru berhasil ditambahkan!');
            } else {
                return back()->with('error', 'Terjadi kesalahan saat menambahkan user.')->withInput();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem. Mohon coba lagi nanti.')->withInput();
        }
    }

    public function deleteUser(Request $request)
    {

        try {
            $idToDelete = $request->userIdToDelete;
            $deleteProceed = $this->userRepository->deleteUser($idToDelete);

            if ($deleteProceed) {
                return redirect()->route('admin.userIndex')->with('success', 'User baru berhasil ditambahkan!');

            } else {

                return response()->json([
                    "message" => "Error, Gagal Menghapus User",
                    "data" => $deleteProceed
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error, Terjadi kesalahan sistem. Mohon coba lagi nanti.',
                'error' => $e->getMessage()
            ], 500);
        }


    }

    public function updateUser(Request $request, $id)
    {
        try {
            $data = $request->all();

            // If password is provided, hash it
            if (!empty($data['password'])) {
                $data['password'] = md5($data['password']);
            } else {
                unset($data['password']);
            }

            $updated = $this->userRepository->updateUser($id, $data);

            if (!$updated) {
                return response()->json([
                    'message' => 'Failed to update user data.',
                ], 400);
            }
            return redirect()->route('admin.userIndex')->with('success', 'User baru berhasil ditambahkan!');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating user data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function showUser(String $id){
        try {
            $userData = $this->userRepository->getUserById($id);

            if(!$userData){
                return response()->json([
                     "message" => "Data User Tidak Ditemukan!",
                ], 404);
            }

            return response()->json([
                 "message" => "Success, Data User Ditemukan!",
                 "data" => $userData
            ], 200);

        } catch (\Exception $e){
            return response()->json([
                "message" => "Error, Terdapat Kendala Di Sistem"
            ], 401);

        }
    }



}
