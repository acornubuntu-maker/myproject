<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            // send guests or non-admins to login (or user home)
            if (! Auth::check()) {
                return redirect()->route('login');
            }
            return redirect()->route('user.home');
        }

        return $next($request);
    }
}