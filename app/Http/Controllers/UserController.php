<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Inertia\Inertia;

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
}