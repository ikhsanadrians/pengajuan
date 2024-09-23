<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('message', 'Login successful');
        }

        return redirect()->route('login')->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->with('message', 'Login failed');
    }
}