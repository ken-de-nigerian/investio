<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileComplete
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get the user's profile onboarding status
        $user = Auth::user();

        // Check if profile exists
        if ($user->profile()->exists()) {
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
