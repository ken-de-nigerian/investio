<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventIfKYCExists
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get the user's KYC status
        $user = Auth::user();

        if ($user->kyc && $user->kyc->status !== 'rejected') {
            // User already has KYC data that isn't rejected
            return redirect()->route('user.kyc')
                ->with('error', 'You have already submitted KYC information.');
        }

        return $next($request);
    }
}
