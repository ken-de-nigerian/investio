<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetConfirmation;
use App\Models\Card;
use App\Models\Goal;
use App\Models\PlanCategory;
use App\Models\Transaction;
use App\Models\UserInvestment;
use App\Models\UserProfile;
use App\Services\Geolocation;
use App\Services\MarketPricesService;
use Exception;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Laravel\Facades\Image;
use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{
    protected MarketPricesService $marketPrices;

    public function __construct(MarketPricesService $marketPrices)
    {
        $this->marketPrices = $marketPrices;
    }

    public function index(Request $request)
    {
        $sort = $request->query('sort');

        $plan_categories = PlanCategory::where('is_active', 1)
            ->orderBy('name')
            ->get();

        $investmentsQuery = UserInvestment::with('plan', 'plan.category')
            ->where('user_id', Auth::id());

        if ($sort === 'completed') {
            $investmentsQuery->where('status', 'completed');
        } elseif ($sort === 'running') {
            $investmentsQuery->where('status', 'running');
        } elseif ($sort === 'liquidated') {
            $investmentsQuery->where('status', 'liquidated');
        } elseif ($sort === 'cancelled') {
            $investmentsQuery->where('status', 'cancelled');
        }

        $investments = $investmentsQuery->latest()->paginate(10);
        $all_investments = UserInvestment::with('plan', 'plan.category')
            ->where('user_id', Auth::id())
            ->get();

        $total_invested = UserInvestment::where('user_id', Auth::id())->sum('amount') / 1000;

        // Calculate additional metrics
        $completed_investments = $all_investments->where('status', 'completed');

        // Calculate profit
        $total_profit = $completed_investments->sum('expected_profit') / 1000;
        $current_value = $total_invested + $total_profit;

        // Category-wise investment data for chart
        $category_data = [];
        foreach ($plan_categories as $category) {
            $category_investments = $all_investments->where('plan.category.id', $category->id);
            $category_data[$category->name] = [
                'running' => $category_investments->where('status', 'running')->sum('amount') / 1000,
                'completed' => $category_investments->where('status', 'completed')->sum('amount') / 1000,
                'liquidated' => $category_investments->where('status', 'liquidated')->sum('amount') / 1000,
                'cancelled' => $category_investments->where('status', 'cancelled')->sum('amount') / 1000,
            ];
        }

        // top perfoming investment
        $top_performing_investment = UserInvestment::with('plan')
            ->where('user_id', Auth::id())
            ->where('status', 'running')
            ->orderByDesc('expected_profit')
            ->first();

        $incomeStats = $this->getMonthlyChange('credit');
        $expenseStats = $this->getMonthlyChange('debit');

        return view('user.dashboard', [
            'title' => 'Account - Dashboard',
            'plan_categories' => $plan_categories,
            'investments' => $investments,
            'all_investments' => $all_investments,
            'sort' => $sort,
            'total_invested' => $total_invested,
            'total_profit' => $total_profit,
            'current_value' => $current_value,
            'category_data' => $category_data,
            'cryptoData' => $this->marketPrices->fetchCoinGeckoCoinList(),
            'goals' => Goal::with('category')
                ->where('user_id', Auth::id())
                ->latest()
                ->take(2)
                ->get(),
            'income' => $incomeStats['amount'],
            'incomeChange' => $incomeStats['change'],
            'incomeClass' => $incomeStats['class'],
            'incomeDirection' => $incomeStats['direction'],
            'expense' => $expenseStats['amount'],
            'expenseChange' => $expenseStats['change'],
            'expenseClass' => $expenseStats['class'],
            'expenseDirection' => $expenseStats['direction'],
            'top_performing_investment' => $top_performing_investment
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

        return view('user.profile.index', [
            'title' => 'Account - Profile',
            'countries' => $countries,
            'cards' => Card::where('user_id', Auth::id())
                ->latest()
                ->get(),
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
     * @throws AuthorizationException
     */
    public function updateAccountDetails (Request $request): RedirectResponse
    {
        $this->authorize('updateProfile', auth()->user());

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'marital_status' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'social_security_number' => 'nullable|string|max:255',
            'account_type' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Prepare profile data
            $data = $request->only(
                'marital_status',
                'occupation',
                'social_security_number',
                'account_type'
            );

            UserProfile::updateOrCreate(
                ['user_id' => Auth::user()->id],
                $data
            );

            return redirect()->back()->with('success', __('Your account details have been updated successfully.'));

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

    /**
     * @param $type
     * @return array
     */
    private function getMonthlyChange($type)
    {
        $now = now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $lastMonth = $now->copy()->subMonth();
        $lastMonthNumber = $lastMonth->month;
        $lastMonthYear = $lastMonth->year;

        $current = Transaction::where('trans_type', $type)
            ->where('user_id', auth()->user()->id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('amount');

        $previous = Transaction::where('trans_type', $type)
            ->where('user_id', auth()->user()->id)
            ->whereMonth('created_at', $lastMonthNumber)
            ->whereYear('created_at', $lastMonthYear)
            ->sum('amount');

        $change = $previous == 0
            ? ($current == 0 ? 0 : 100)
            : round((($current - $previous) / $previous) * 100, 2);

        return [
            'amount' => $current,
            'change' => $change,
            'direction' => $change >= 0 ? 'up' : 'down',
            'class' => $change >= 0 ? 'text-success' : 'text-danger',
        ];
    }
}
