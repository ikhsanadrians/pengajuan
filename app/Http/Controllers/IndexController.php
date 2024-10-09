<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TransaksiRepository;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __construct(TransaksiRepository $transaksiRepository)
    {
        $this->transaksiRepository = $transaksiRepository;
    }

    public function index()
    {
        $transaksis = $this->transaksiRepository->getAllTransaksisAdmin();


        return Inertia::render('Home',['transaksis' => $transaksis]);
    }
}