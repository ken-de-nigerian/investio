<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use App\Services\Geolocation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class OnboardingController extends Controller
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
     * Show the onboarding step by step form
     */
    public function index()
    {
        $countriesResponse = Geolocation::handleLocationRequest('countries');
        $countries = $countriesResponse->getData(true)['data'] ?? [];

        return view('auth.onboarding.index', [
            'title' => 'Onboarding',
            'countries' => $countries,
        ]);
    }

    /**
     * Store the newly onboarded user's data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'social_security_number' => 'nullable|string|max:255',
            'account_type' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('avatar')) {
                $imagePath = $this->handleImageUpload($request->file('avatar'));
                if (!$imagePath) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to upload avatar. Please try again.'
                    ], 500);
                }
            }

            // Update the user's account
            Auth::user()->update([
                'first_name' => ucfirst($request->first_name),
                'last_name' => ucfirst($request->last_name),
                'phone_number' => $request->phone_number,
                'avatar' => $imagePath
            ]);

            // Create user profile
            $profileData = [
                'account_type' => $request->account_type,
                'account_number' => $this->account_number(),
                'account_pin' => rand(1000, 9999),
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'address' => $request->address,
                'occupation' => $request->occupation,
                'social_security_number' => $request->social_security_number,
            ];

            UserProfile::updateOrCreate(
                ['user_id' => Auth::user()->id],
                $profileData
            );

            return response()->json([
                'success' => true,
                'redirect' => $this->redirectTo(),
                'message' => __('Your personal details have been updated successfully.')
            ], 201);

        } catch (Exception $exception) {
            Log::error('Failed to update profile: ' . $exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => __('Failed to update profile')
            ], 500);
        }
    }

    /**
     * Handle image upload and return the path
     */
    private function handleImageUpload($file)
    {
        try {

            $storagePath = 'profile/';

            // Store new image
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $fullPath = $storagePath . $filename;

            // Resize and save
            $resizedImage = Image::read($file)->resize(1024, 1024);
            Storage::disk('public')->put($fullPath, $resizedImage->encode());

            // Store in the profile directory within storage/app/public
            return Storage::disk('public')->url($fullPath);
        } catch (Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate an account number.
     *
     * @return string A randomly generated account number.
     */
    protected function account_number(): string
    {
        $random_number = mt_rand(1000000000, 9999999999);
        $padded_number = str_pad($random_number, 10, '0', STR_PAD_LEFT);
        return "00" . $padded_number;
    }
}
