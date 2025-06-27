<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialLoginController extends Controller
{
    protected array $supportedProviders = ['google', 'facebook'];

    /**
     * Redirect to provider's authentication page
     */
    public function redirectToProvider(string $provider)
    {
        if (!in_array($provider, $this->supportedProviders)) {
            return view('auth.login', [
                'errors' => ['provider' => __('auth.unsupported_provider', ['provider' => $provider])],
                'supported_providers' => $this->supportedProviders,
                'social_login' => config('settings.login.social_enabled')
            ]);
        }

        try {
            return Socialite::driver($provider)->stateless()->redirect();
        } catch (Exception $e) {
            Log::error("Socialite redirect failed for $provider: " . $e->getMessage());
            return view('auth.login', [
                'errors' => ['provider' => __('auth.provider_connection_failed')],
                'social_login' => config('settings.login.social_enabled')
            ]);
        }
    }

    /**
     * Handle provider callback
     * @throws Throwable
     */
    public function handleProviderCallback(string $provider)
    {
        if (!in_array($provider, $this->supportedProviders)) {
            return view('auth.login', [
                'errors' => ['provider' => __('auth.unsupported_provider', ['provider' => $provider])],
                'supported_providers' => $this->supportedProviders,
                'social_login' => config('settings.login.social_enabled')
            ]);
        }

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            if (!$socialUser->getEmail()) {
                return view('auth.login', [
                    'errors' => ['provider' => __('auth.no_email')],
                    'social_login' => config('settings.login.social_enabled')
                ]);
            }

            $user = $this->findOrCreateUser($provider, $socialUser);
            Auth::login($user, true);

            return $this->sendLoginResponse();
        } catch (Exception $e) {
            Log::error("Social login failed for $provider: " . $e->getMessage());
            return view('auth.login', [
                'errors' => ['provider' => $e->getMessage()],
                'social_login' => config('settings.login.social_enabled')
            ]);
        }
    }

    /**
     * Find or create a user with provider locking
     * @throws Throwable
     */
    protected function findOrCreateUser(string $provider, $socialUser): User
    {
        return DB::transaction(function () use ($provider, $socialUser) {
            // First, try to find provider-specific credentials
            $user = User::where('social_login_id', $socialUser->getId())
                ->where('social_login_provider', $provider)
                ->first();

            // If not found, try by email
            if (!$user && $socialUser->getEmail()) {
                $user = User::where('email', $socialUser->getEmail())->first();

                // Prevent login if email is associated with a different social provider
                if ($user &&
                    !empty($user->social_login_provider) &&
                    in_array($user->social_login_provider, $this->supportedProviders) &&
                    $user->social_login_provider !== $provider
                ) {
                    throw new Exception(__('auth.provider_conflict', ['provider' => $user->social_login_provider]));
                }
            }

            return $user
                ? $this->updateExistingUser($user, $provider, $socialUser)
                : $this->createNewUser($provider, $socialUser);
        });
    }

    /**
     * Update existing user's provider info
     */
    protected function updateExistingUser(User $user, string $provider, $socialUser): User
    {
        $user->update([
            'social_login_provider' => $provider,
            'social_login_id' => $socialUser->getId(),
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        return $user;
    }

    /**
     * Create new user from provider data
     */
    protected function createNewUser(string $provider, $socialUser): User
    {
        [$firstname, $lastname] = $this->extractNames($provider, $socialUser);

        return User::create([
            'avatar' => $socialUser->getAvatar(),
            'first_name' => $this->sanitizeName($firstname),
            'last_name' => $this->sanitizeName($lastname),
            'email' => $socialUser->getEmail(),
            'social_login_provider' => $provider,
            'social_login_id' => $socialUser->getId(),
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Extract and format names from provider data
     */
    protected function extractNames(string $provider, $socialUser): array
    {
        $name = $socialUser->getName() ?? '';

        switch ($provider) {
            case 'google':
                return [
                    $socialUser->user['given_name'] ?? $name,
                    $socialUser->user['family_name'] ?? ''
                ];

            default:
                $parts = explode(' ', $name, 2);
                return [
                    $parts[0] ?? $name,
                    $parts[1] ?? ''
                ];
        }
    }

    /**
     * Sanitize and format names
     */
    protected function sanitizeName(string $name): string
    {
        $name = trim(preg_replace('/\s+/', ' ', $name));
        return $name ? mb_convert_case($name, MB_CASE_TITLE) : 'Unknown';
    }

    /**
     * Send a successful login response
     */
    protected function sendLoginResponse(): RedirectResponse
    {
        if (Gate::allows('access-admin-dashboard')) {
            $redirectUrl = route('admin.dashboard');
        } elseif (Gate::allows('access-user-dashboard')) {
            $redirectUrl = route('user.dashboard');
        } else {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => __('auth.no_dashboard_access'),
            ]);
        }

        return redirect()->intended($redirectUrl)
            ->with('success', __('auth.login_success'));
    }
}
