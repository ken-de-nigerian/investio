<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetConfirmation;
use App\Models\User;
use App\Models\PasswordResetToken;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetLink;
use Jenssegers\Agent\Agent;

class ForgotPasswordController extends Controller
{
    /**
     * Show the password-reset request form.
     */
    public function create()
    {
        return view('auth.passwords.email', [
            'title' => 'Account - Forgot Password',
        ]);
    }

    /**
     * Send a password reset link to the given user.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
        ], [
            'email.exists' => __('We can\'t find a user with that email address.'),
        ]);

        // Throttle settings
        $throttleDelay = 60; // 60 seconds

        if (RateLimiter::tooManyAttempts($this->throttleKey($request->email), 1)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request->email));
            return back()->withErrors([
                'email' => __('Please wait small before retrying.'),
                'throttle' => $seconds
            ]);
        }

        RateLimiter::hit($this->throttleKey($request->email), $throttleDelay);

        // Find the user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => __('User not found.')]);
        }

        // Generate token
        $token = Str::random(60);

        // Create or update the password reset record
        PasswordResetToken::updateOrCreate(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Generate reset URL
        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $user->email
        ]);

        // Send email
        if (config('settings.email_notification')) {
            Mail::mailer(config('settings.email_provider'))->to($user->email)->send(new PasswordResetLink($resetUrl, $user));
        }

        return back()->with('success', __('Password reset link sent to your email.'));
    }

    protected function throttleKey(string $email): string
    {
        return 'password-reset|'.$email;
    }

    /**
     * Show the password reset form.
     */
    public function edit(Request $request, string $token)
    {
        return view('auth.passwords.reset', [
            'title' => 'Account - Reset Password',
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    /**
     * Reset the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Find the reset record
        $resetRecord = PasswordResetToken::where('email', $request->email)->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => __('Invalid or expired token.')]);
        }

        // Check if the token is expired (e.g., 60 minutes)
        $createdAt = Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            $resetRecord->delete();
            return back()->withErrors(['email' => __('Token has expired.')]);
        }

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => __('User not found.')]);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the used token
        $resetRecord->delete();

        // Authenticate the user
        Auth::login($user);

        // Send email
        if (config('settings.email_notification')) {
            Mail::mailer(config('settings.email_provider'))
                ->to($user->email)
                ->send(new PasswordResetConfirmation(
                    $user,
                    $request->ip(),
                    $this->getDevice($request->userAgent())
                ));
        }

        // Redirect based on role
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard')->with('success', __('Password reset successfully.')),
            'user' => redirect()->route('user.dashboard')->with('success', __('Password reset successfully.')),
            default => redirect()->route('login'),
        };
    }

    /**
     * @param $userAgent
     * @return string
     */
    protected function getDevice($userAgent)
    {
        $parser = new Agent();
        $parser->setUserAgent($userAgent);

        $device = $parser->device();
        $platform = $parser->platform();
        $browser = $parser->browser();

        return $device . ' (' . $platform . ') - ' . $browser;
    }
}
