<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (Auth::check()) {

            $user = Auth::user();

            if ($user->role == 'user'){
                return redirect()->route('user.dashboard');
            }

            if ($user->role == 'admin'){
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
