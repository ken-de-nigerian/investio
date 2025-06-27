<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetConfirmation;
use App\Models\UserProfile;
use App\Services\Geolocation;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Laravel\Facades\Image;
use Jenssegers\Agent\Agent;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'title' => 'Account - Dashboard',
        ]);
    }

    /**
     * Renders profile tab
     *
     * @throws AuthorizationException
     */
    public function profilePage()
    {
        $this->authorize('viewProfile', auth()->user());

        $countriesResponse = Geolocation::handleLocationRequest('countries');
        $countries = $countriesResponse->getData(true)['data'] ?? [];

        return view('admin.profile.index', [
            'title' => 'Account - Profile',
            'countries' => $countries,
        ]);
    }

    /**
     * Update the user's profile picture.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function updateProfilePicture(Request $request): JsonResponse
    {
        $this->authorize('updateProfile', auth()->user());

        $request->validate([
            'profile_image' => [
                'required',
                'image',
                'mimes:png,jpg,jpeg',
                'max:2048',
            ],
        ], [
            'profile_image.required' => 'Please select an image to upload.',
            'profile_image.image' => 'The file must be an image.',
            'profile_image.mimes' => 'The image must be a PNG, JPG, or JPEG file.',
            'profile_image.max' => 'The image must not exceed 2MB in size.',
        ]);

        try {

            $user = Auth::user();
            $storagePath = 'profile/';

            // Delete old image if exists
            if ($user->avatar) {
                $oldImagePath = str_replace(Storage::disk('public')->url(''), '', $user->avatar);
                Storage::disk('public')->delete($oldImagePath);
            }

            // Store new image
            $image = $request->file('profile_image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $fullPath = $storagePath . $filename;

            // Resize and save
            $resizedImage = Image::read($image)->resize(124, 124);
            Storage::disk('public')->put($fullPath, $resizedImage->encode());

            // Update user
            $user->update([
                'avatar' => Storage::disk('public')->url($fullPath)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => __('Profile picture successfully uploaded.')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (Exception $e) {
            Log::error("Profile picture update error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Remove the user's profile picture.
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function removeProfilePicture(): RedirectResponse
    {
        $this->authorize('updateProfile', auth()->user());

        $user = Auth::user();

        // Delete the profile picture if it exists
        if ($user->avatar) {
            $oldImagePath = str_replace(Storage::disk('public')->url(''), '', $user->avatar);

            try {
                Storage::disk('public')->delete($oldImagePath);
            } catch (Exception) {
                return redirect()->back()
                    ->withErrors(['error' => 'Failed to delete the profile picture. Please try again.']);
            }
        }

        // Set the profile image to null in the database
        $user->updateOrFail([
            'avatar' => null,
        ]);

        // Redirect back with a success message
        return redirect()->back()
            ->with('success', 'Profile picture removed successfully.');
    }

    /**
     * Updates Account Information
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $this->authorize('updateProfile', auth()->user());

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Update the user's profile
            Auth::user()->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'phone_number' => $request->input('phone_number'),
            ]);

            // Prepare profile data
            $data = $request->only(
                'date_of_birth',
                'gender',
                'country',
                'state',
                'city',
                'address'
            );

            UserProfile::updateOrCreate(
                ['user_id' => Auth::user()->id],
                $data
            );

            return redirect()->back()->with('success', __('Your personal details have been updated successfully.'));

        } catch (Exception $exception) {
            Log::error('Failed to update profile: ' . $exception->getMessage());
            return redirect()->back()->with('error', __('Failed to update profile'));
        }
    }

    /**
     * Update the user's password.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $this->authorize('updateProfile', auth()->user());

        // Validate the request data
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ], [
            'current_password.required' => 'Current password is required.',
            'password.required' => 'New password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = Auth::user();

        // Verify the current password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->with('error', __('The current password is incorrect.'))->withInput();
        }

        // Update the user's password
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

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

        // Return appropriate response
        return redirect()->back()
            ->with('success', __('Your password has been updated successfully.'));
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
