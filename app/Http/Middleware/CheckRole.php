<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        // if (!$request->user()) {
        //     return redirect()->route('login')->with('error', 'You do not have access to this section.');
        // }

        if ($request->is('login') || $request->is('login/*')) {
            return $next($request);
        }

        $user = Auth::user();


        @dd($user);

        if ($request->user()->role_id == 1) {
            return redirect()->route('admin');
        } elseif ($request->user()->role_id == 2) {
            return redirect()->route('owner');
        } elseif ($request->user()->role_id == 3) {
            return redirect()->route('user');
        }

        return $next($request);
    }
}
