<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends Controller
{
    protected array $supportedProviders = ['google', 'facebook'];

    /**
     * Logout the admin user.
     */
    public function destroy(Request $request): Response
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->flush(); // Clears all session data
        $request->session()->regenerateToken();

        // Redirect to the login page after logout
        return redirect()->route('login')->with('success', __('You have been logged out.'));
    }

    /**
     * @param $sessionId
     * @return RedirectResponse
     */
    public function destroySession($sessionId): RedirectResponse
    {
        // Delete the session with the given ID from the `sessions` table
        DB::table('sessions')->where('id', $sessionId)->delete();

        // If the deleted session is the current session, log the user out
        if ($sessionId === session()->getId()) {
            Auth::logout();
        }

        // Redirect back to the previous page with a success message
        return redirect()->back()->with('success', __('Session logged out successfully.'));
    }

    /**
     * @return RedirectResponse
     */
    public function destroyAllSessions(): RedirectResponse
    {
        // Get the ID of the currently authenticated user
        $userId = Auth::id();

        // Delete all sessions for the authenticated user from the `sessions` table
        DB::table('sessions')->where('user_id', $userId)->delete();

        // Log the user out
        Auth::logout();

        // Redirect back to the previous page with a success message
        return redirect()->back()->with('success', __('All sessions logged out successfully.'));
    }

    /**
     * @param string $provider
     * @return RedirectResponse
     */
    public function invokeAccount(string $provider): RedirectResponse
    {
        if (!in_array($provider, $this->supportedProviders)) {
            return redirect()->back()->with('error',  __('auth.unsupported_provider', ['provider' => $provider]));
        }

        $user = Auth::user();
        $user->update([
            'social_login_provider' => null,
            'social_login_id' => null
        ]);

        return redirect()->back()->with('success', __('Account information updated successfully.'));
    }
}
