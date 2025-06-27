<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected int $maxAttempts = 5; // Max login attempts before lockout
    protected int $decayMinutes = 1; // Lockout duration in minutes

    /**
     * Render the login page
     */
    public function index()
    {
        return view('auth.login', [
            'title' => 'Account - Login',
            'social_login' => config('settings.login.social_enabled'),
        ]);
    }

    /**
     * Log the user in
     */
    public function login(Request $request)
    {
        // Validate login request
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', Password::defaults()],
        ]);

        // Check if a user has too many failed login attempts
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        // Attempt login first (DO NOT invalidate session yet)
        if ($this->attemptLogin($request)) {
            // Regenerate session only if login is successful
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            return $this->sendLoginResponse();
        }

        // Increment login attempts if failed
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get login credentials from request.
     */
    protected function credentials(Request $request): array
    {
        return $request->only('email', 'password');
    }

    /**
     * Attempt to log in with provided credentials.
     */
    protected function attemptLogin(Request $request): bool
    {
        return Auth::attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }

    /**
     * Send a successful login response.
     */
    protected function sendLoginResponse(): RedirectResponse
    {
        if (Gate::allows('access-admin-dashboard')) {
            $redirectUrl = route('admin.dashboard');
        } elseif (Gate::allows('access-user-dashboard')) {
            $redirectUrl = route('user.dashboard');
        } else {
            Auth::logout();
            return redirect()->route('auth.login')->withErrors([
                'email' => __('Your account does not have access to any dashboard.')
            ]);
        }

        return redirect()->intended($redirectUrl)
            ->with('success', __('You are now logged in.'));
    }

    /**
     * Send a failed login response.
     */
    protected function sendFailedLoginResponse(Request $request): RedirectResponse
    {
        $errorMessage = __('These credentials do not match our records.');

        // Always redirect back with errors
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $errorMessage]);
    }

    /**
     * Get the field used for authentication.
     */
    public function username(): string
    {
        return 'email';
    }

    /**
     * Handle a lockout response when too many logins attempt to occur.
     *
     * @throws ValidationException
     */
    protected function sendLockoutResponse(Request $request): RedirectResponse
    {
        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        $errorMessage = trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]);

        // Always throw a validation exception with a lockout message
        throw ValidationException::withMessages([
            $this->username() => [$errorMessage],
        ])->status(429);
    }

    /**
     * Clear failed login attempts for the user.
     */
    protected function clearLoginAttempts(Request $request): void
    {
        RateLimiter::clear($this->throttleKey($request));
    }

    /**
     * Get the unique key for tracking login attempts.
     */
    protected function throttleKey(Request $request): string
    {
        return mb_strtolower($request->input($this->username())).'|'.$request->ip();
    }

    /**
     * Check if the user has exceeded maximum login attempts.
     */
    protected function hasTooManyLoginAttempts(Request $request): bool
    {
        return RateLimiter::tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts
        );
    }

    /**
     * Increment the login attempt count.
     */
    protected function incrementLoginAttempts(Request $request): void
    {
        RateLimiter::hit(
            $this->throttleKey($request),
            $this->decayMinutes * 60
        );
    }
}
