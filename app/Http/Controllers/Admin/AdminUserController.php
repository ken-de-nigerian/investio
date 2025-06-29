<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountBlockedConfirmation;
use App\Mail\AccountDeletedConfirmation;
use App\Mail\CardCreatedConfirmation;
use App\Mail\FundsManagedConfirmation;
use App\Mail\PasswordResetConfirmation;
use App\Mail\UserEmailConfirmation;
use App\Models\Alert;
use App\Models\Card;
use App\Models\Deposit;
use App\Models\DomesticTransfer;
use App\Models\Goal;
use App\Models\InterBankTransfer;
use App\Models\Loan;
use App\Models\Referral;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInvestment;
use App\Models\UserProfile;
use App\Models\WireTransfer;
use App\Services\Geolocation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Jenssegers\Agent\Agent;
use Throwable;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('referrer')->where('role', '!=', 'admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', [
            'title' => 'Account - Users',
            'users' => $users
        ]);
    }

    public function create()
    {
        $countriesResponse = Geolocation::handleLocationRequest('countries');
        $countries = $countriesResponse->getData(true)['data'] ?? [];

        return view('admin.users.create', [
            'title' => 'Account - Create User',
            'countries' => $countries
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
            'email' => 'required|string|email|max:255|unique:users',
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
            'password' => 'required|string|min:8',
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

            // Create the user's account
            $user = User::create([
                'first_name' => ucfirst($request->first_name),
                'last_name' => ucfirst($request->last_name),
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
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
                ['user_id' => $user->id],
                $profileData
            );

            return response()->json([
                'success' => true,
                'redirect' => route('admin.users'),
                'message' => "Account for $request->first_name $request->last_name has been created successfully."
            ], 201);

        } catch (Exception $exception) {
            Log::error('Failed to create account: ' . $exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => __('Failed to create account')
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Deposits
        $total_deposits = Deposit::where('user_id', $user->id)->sum('amount');
        $pending_deposits = Deposit::where('user_id', $user->id)->where('status', 'pending')->sum('amount');
        $completed_deposits = Deposit::where('user_id', $user->id)->where('status', 'completed')->sum('amount');
        $rejected_deposits = Deposit::where('user_id', $user->id)->where('status', 'rejected')->sum('amount');
        $deposits = Deposit::where('user_id', $user->id)->latest()->paginate(10)->withQueryString();

        // Traansfers
        $interbank_transfers = InterbankTransfer::where('user_id', $user->id)->latest()->paginate(10)->withQueryString();
        $domestic_transfers = DomesticTransfer::where('user_id', $user->id)->latest()->paginate(10)->withQueryString();
        $wire_transfers = WireTransfer::where('user_id', $user->id)->latest()->paginate(10)->withQueryString();

        // Goals & Savings
        $goals = Goal::with('category')->where('user_id', $user->id)->latest()->paginate(10)->withQueryString();

        // Loans
        $loans = Loan::where('user_id', $user->id)->latest()->paginate(10)->withQueryString();

        // Investments
        $investments = UserInvestment::where('user_id', $user->id)->latest()->paginate(10)->withQueryString();

        // Referrals
        $referrals = $user->referrals()->latest()->paginate(10)->withQueryString();

        // Commissions
        $commissions = Referral::with('referredUser', 'investment.plan')
            ->where('to_id', $user->id)->latest()->paginate(10)->withQueryString();

        // Transactions
        $transactions = Transaction::where('user_id', $user->id)->latest()->paginate(10)->withQueryString();

        // Metric Data
        $total_goals = Goal::where('user_id', $user->id)->sum('current_amount');
        $total_loans = Loan::where('user_id', $user->id)->sum('loan_amount');
        $total_referrals = User::where('ref_by', $user->id)->count();
        $referral_commissions = Referral::where('to_id', $user->id)->sum('amount');
        $total_investment = UserInvestment::where('user_id', $user->id)->sum('amount');

        return view('admin.users.show', [
            'title' => 'Account - User',
            'metric' => [
                'total_deposits' => $total_deposits,
                'pending_deposits' => $pending_deposits,
                'completed_deposits' => $completed_deposits,
                'rejected_deposits' => $rejected_deposits,
                'total_goals' => $total_goals,
                'total_loans' => $total_loans,
                'total_referrals' => $total_referrals,
                'referral_commissions' => $referral_commissions,
                'total_investment' => $total_investment,
            ],
            'user' => $user,
            'referrals' => $referrals,
            'profile_progress' => $this->calculateProfileProgress($user),
            'deposits' => $deposits,
            'interbank_transfers' => $interbank_transfers,
            'domestic_transfers' => $domestic_transfers,
            'wire_transfers' => $wire_transfers,
            'goals' => $goals,
            'loans' => $loans,
            'investments' => $investments,
            'commissions' => $commissions,
            'transactions' => $transactions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $countriesResponse = Geolocation::handleLocationRequest('countries');
        $countries = $countriesResponse->getData(true)['data'] ?? [];

        return view('admin.users.edit', [
            'title' => 'Account - User',
            'user' => $user,
            'countries' => $countries,
            'cards' => Card::where('user_id', $user->id)
                ->latest()
                ->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
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
            $user->update([
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
                ['user_id' => $user->id],
                $data
            );

            return redirect()->back()->with('success', __('User\'s personal details have been updated successfully.'));

        } catch (Exception $exception) {
            Log::error('Failed to update profile: ' . $exception->getMessage());
            return redirect()->back()->with('error', __('Failed to update profile'));
        }
    }

    /**
     * Update the user's profile picture.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function updateProfilePicture(Request $request, User $user): JsonResponse
    {
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
                'message' => __('Users\'s profile picture successfully uploaded.')
            ]);

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
     * @param User $user
     * @return RedirectResponse
     * @throws Throwable
     */
    public function removeProfilePicture(User $user): RedirectResponse
    {
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
            ->with('success', 'User\'s profile picture removed successfully.');
    }

    /**
     * Updates the user's account details
     */
    public function updateAccountDetails (Request $request, User $user): RedirectResponse
    {

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
                ['user_id' => $user->id],
                $data
            );

            return redirect()->back()->with('success', __('User\'s account details have been updated successfully.'));

        } catch (Exception $exception) {
            Log::error('Failed to update profile: ' . $exception->getMessage());
            return redirect()->back()->with('error', __('Failed to update profile'));
        }
    }

    /**
     * Store a newly created credit card in storage.
     */
    public function cardStore(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'card_holder' => 'required|string|max:255',
            'card_number' => [
                'required',
                'string',
                'size:19',
                'regex:/^[0-9]{4} [0-9]{4} [0-9]{4} [0-9]{4}$/',
            ],
            'expiry_month' => 'required|numeric|between:1,12',
            'expiry_year' => 'required|numeric|digits:4|after_or_equal:' . date('Y'),
            'cvv' => 'required|numeric|digits:3',
        ]);

        // Additional validation for expiration date
        $currentYear = date('Y');
        $currentMonth = date('m');

        if ($validated['expiry_year'] == $currentYear && $validated['expiry_month'] < $currentMonth) {
            return back()->withErrors(['expiry_month' => 'The card has already expired.']);
        }

        try {

            $expiryDate = str_pad($validated['expiry_month'], 2, '0', STR_PAD_LEFT) . '/' . substr($validated['expiry_year'], 2);

            // Create the card record
            $card = Card::create([
                'user_id' => $user->id,
                'card_balance' => $user->balance,
                'serial_key' => $this->generateSerialKey(),
                'card_number' => $this->maskCardNumber($validated['card_number']),
                'card_name' => $validated['card_holder'],
                'card_expiration' => $expiryDate,
                'card_security' => encrypt($validated['cvv']),
                'card_type' => $this->determineCardType($validated['card_number']),
                'card_status' => 'active',
            ]);

            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))->to($user->email)->send(new CardCreatedConfirmation($card));
            }

            return redirect()->route('admin.users.edit', ['user' => $user->id, 'tab' => 'cards'])
                ->with('success', 'User\'s credit card has been added successfully!');

        } catch (Exception $exception) {
            Log::error('Failed to add card' . $exception->getMessage());
            return back()->withErrors(['error' => 'Failed to add card. Please try again.']);
        }
    }

    /**
     * Calculate the profile completion percentage.
     *
     * @param User $user
     * @return float
     */
    private function calculateProfileProgress(User $user): float
    {
        $requiredFields = [
            'account_type',
            'account_number',
            'account_pin',
            'date_of_birth',
            'gender',
            'marital_status',
            'country',
            'state',
            'city',
            'address',
            'occupation',
            'social_security_number'
        ];

        $totalFields = count($requiredFields);
        $filledFields = 0;

        foreach ($requiredFields as $field) {
            if (!empty($user->profile->$field)) {
                $filledFields++;
            }
        }

        return round(($filledFields / $totalFields) * 100);
    }

    /**
     * Manage user funds (deposit or withdraw).
     */
    public function manageFunds(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:deposit,withdraw',
            'note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {

            $amount = $request->amount;
            $type = $request->type;

            // Determine transaction nature
            $transType = $type === 'deposit' ? 'credit' : 'debit';
            $description = $type === 'deposit'
                ? 'Funds credited to your account'
                : 'Funds withdrawn from your account';

            // Check withdrawal limit
            if ($type === 'withdraw' && $user->balance < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance, amount is higher than the user\'s balance'
                ], 422);
            }

            // Update balance
            $user->balance = $type === 'deposit'
                ? $user->balance + $amount
                : $user->balance - $amount;

            $user->save();

            // Create alert
            Alert::create([
                'user_id' => $user->id,
                'sender_name' => config('app.name'),
                'sender_bank' => config('app.name'),
                'amount' => $amount,
                'trans_type' => $transType,
                'status' => 'approved',
                'date' => now(),
            ]);

            // Log transaction
            $transactionId = strtoupper($transType) . '_' . Str::uuid();

            Transaction::create([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $amount,
                'bank_name' => config('app.name'),
                'account_number' => $user->profile->account_number,
                'trans_type' => $transType,
                'receiver_name' => $user->full_name ?? $user->first_name . ' ' . $user->last_name,
                'description' => $description,
                'acct_type' => $user->profile->acct_type ?? 'savings',
                'trans_status' => 'approved'
            ]);

            // Send notifications
            if (config('settings.email_notification')) {
                // Send confirmation to the user
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new FundsManagedConfirmation($user, $amount, $type));
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Funds updated successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Funds management failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }

    /**
     * Send email to user.
     */
    public function sendEmail(Request $request, User $user)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {

            // Send notifications
            if (config('settings.email_notification')) {
                // Send confirmation to the user
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new UserEmailConfirmation($user, $request->subject, $request->message));
            }

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Email sending failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to send email'], 500);
        }
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {

            $user->password = Hash::make($request->password);
            $user->save();

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

            return response()->json(['success' => true, 'message' => 'Password reset successfully']);
        } catch (Exception $e) {
            Log::error('Password reset failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to reset password'], 500);
        }
    }

    /**
     * Block user account.
     */
    public function block(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {

            $user->status = 'inactive';
            $user->save();

            // Send email
            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new AccountBlockedConfirmation($user, $request->reason));
            }

            return response()->json(['success' => true, 'message' => 'Account blocked successfully']);
        } catch (Exception $e) {
            Log::error('Account blocking failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to block account'], 500);
        }
    }

    /**
     * Delete user account.
     */
    public function delete(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {

            // Send email
            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new AccountDeletedConfirmation($user, $request->reason));
            }

            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Account deletion failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to delete account'], 500);
        }
    }

    /**
     * Login as user.
     */
    public function loginAsUser(Request $request, User $user)
    {
        try {
            Auth::login($user);
            return redirect()->route('user.dashboard');
        } catch (Exception $e) {
            Log::error('Login as user failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to login as user'], 500);
        }
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
     * Determine card type based on number
     */
    protected function determineCardType($cardNumber)
    {
        $cardNumber = str_replace(' ', '', $cardNumber);
        $firstDigit = substr($cardNumber, 0, 1);
        $firstTwoDigits = substr($cardNumber, 0, 2);
        $firstFourDigits = substr($cardNumber, 0, 4);

        return match(true) {
            // Visa (starts with 4)
            $firstDigit === '4' => 'Visa',

            // Mastercard (starts with 51-55 or 2221-2720)
            $firstDigit === '5' && $firstTwoDigits >= 51 && $firstTwoDigits <= 55 => 'Mastercard',
            $firstFourDigits >= 2221 && $firstFourDigits <= 2720 => 'Mastercard',

            // American Express (starts with 34 or 37)
            $firstTwoDigits === '34' || $firstTwoDigits === '37' => 'American Express',

            // Discover (starts with 6011, 644-649, 65)
            str_starts_with($cardNumber, '6011') => 'Discover',
            ($firstThreeDigits = substr($cardNumber, 0, 3)) >= 644 && $firstThreeDigits <= 649 => 'Discover',
            str_starts_with($cardNumber, '65') => 'Discover',

            // Other less common types
            $firstTwoDigits === '62' => 'China UnionPay',
            $firstFourDigits >= 2200 && $firstFourDigits <= 2204 => 'Mir',

            // Default fallback
            default => 'Unknown'
        };
    }

    /**
     * @return string
     */
    protected function generateSerialKey()
    {
        return Str::upper(Str::random(3)) . '-' .
            mt_rand(1000, 9999) . '-' .
            Str::upper(Str::random(3)) . '-' .
            mt_rand(1000, 9999);
    }

    /**
     * @param $cardNumber
     * @return string
     */
    protected function maskCardNumber($cardNumber)
    {
        $cleaned = preg_replace('/\s+/', '', $cardNumber);
        return substr($cleaned, 0, 4) . '******' . substr($cleaned, -4);
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
