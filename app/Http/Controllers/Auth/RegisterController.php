<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ReferralSuccess;
use App\Models\User;
use App\Models\UserProfile;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @return string
     */
    protected function redirectTo(): string
    {
        if (Gate::allows('access-admin-dashboard')) {
            return route('admin.dashboard');
        }

        if (Gate::allows('access-user-dashboard')) {
            return route('user.dashboard');
        }

        Auth::logout();
        return route('login');
    }

    /**
     * Show the application registration form.
     *
     * @param Request $request
     * @return RedirectResponse|Renderable
     */
    public function index(Request $request): RedirectResponse|Renderable
    {
        if (!config('settings.register.enabled')) {
            return redirect()->back()->with('error', 'Registration disabled. Please contact the administrator.');
        }

        $request->session()->put('referral', $request->query('ref'));

        return view('auth.register', [
            'title' => 'Account - Register',
            'social_login' => config('settings.login.social_enabled'),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function register(Request $request): RedirectResponse
    {
        $this->validator($request->all())->validate();

        // Get referral info
        $referralCode = session()->get('referral');
        $referrer = null;

        if ($referralCode) {
            $referrerProfile = UserProfile::where('account_number', $referralCode)->first();
            if ($referrerProfile) {
                $referrer = User::find($referrerProfile->user_id);
            }
        }

        // Create user
        $userData = $request->all();
        $userData['ref_by'] = $referrer?->id;

        event(new Registered($user = $this->create($userData)));

        Auth::login($user);

        // Send emails if enabled
        if (config('settings.email_notification')) {
            Mail::mailer(config('settings.email_provider'))->to($user->email)
                ->send(new \App\Mail\Registered($user));

            if ($referrer) {
                Mail::mailer(config('settings.email_provider'))->to($referrer->email)
                    ->send(new ReferralSuccess($referrer, $user));
            }
        }

        // Check if profile exists
        if (!$user->profile) {
            return redirect()->route('onboarding');
        }

        return redirect()->intended($this->redirectTo());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', Password::defaults()]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     * @throws Exception
     */
    protected function create(array $data): User
    {
        try {
            return User::create([
                'ref_by' => $data['ref_by'] ?? null,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'user'
            ]);
        } catch (Exception $e) {
            Log::error('User Registration Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
