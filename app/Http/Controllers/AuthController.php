<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Show the login page.
     *
     * @return \Inertia\Response
     */
    public function login()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle login form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginPost(Request $request)
    {
        // Validate the login form inputs
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate using the provided credentials
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();


            $user = Auth::user();

            if ($user) {
                switch ($user->role_id) {
                    case 1:
                        return redirect()->route('admin');
                    case 2:
                        return redirect()->route('owner');
                    case 3:
                        return redirect()->route('user');
                }
            }

        }

        // If authentication fails, redirect back to the login page with an error
        return redirect()->route('login')->withErrors([
            'username' => 'Username atau password anda salah!',
        ])->with('message', 'Login failed');
    }

    /**
     * Handle user logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the user's session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect()->route('login')->with('message', 'You have been logged out successfully.');
    }
}
