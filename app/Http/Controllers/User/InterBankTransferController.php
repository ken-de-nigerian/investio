<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\InterBankTransferConfirmation;
use App\Mail\OtpMail;
use App\Models\InterBankTransfer;
use App\Models\OtpCode;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserProfile;
use App\Notifications\NewInterBankTransferNotification;
use App\Notifications\RecipientInterBankTransferNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Str;
use Throwable;

class InterBankTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'account_number' => 'required|digits_between:10,16',
        ]);

        try {

            $profile = UserProfile::with('user')
                ->where('account_number', $request->account_number)
                ->first();

            if (!$profile || !$profile->user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Account not found or incomplete profile',
                ], 404);
            }

            $otp = random_int(1000, 9999);
            $otpExpiry = Carbon::now()->addMinutes(5);

            OtpCode::updateOrCreate(
                ['user_id' => auth()->user()->id],
                ['code' => $otp, 'expires_at' => $otpExpiry]
            );

            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))->to(auth()->user()->email)->send(new OtpMail($otp, auth()->user()));
            }

            return response()->json([
                'status' => 'success',
                'fullname' => $profile->user->first_name . ' ' . $profile->user->last_name,
                'acct_type' => $profile->account_type,
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to fetch account details: ' . $exception->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch account details. Please try again later.'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|digits_between:10,16',
            'amount' => 'required|numeric|min:0.01',
            'code' => 'required|digits:4'
        ]);

        try {

            $user = Auth::user();

            // Verify OTP
            $code = OtpCode::where('user_id', $user->id)
                ->where('code', $validated['code'])
                ->first();

            if (!$code) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP. Please try again.'
                ], 422);
            }

            if (Carbon::now()->greaterThan($code->expires_at)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has expired. Request a new one.'
                ], 422);
            }

            // Check balance
            if ($validated['amount'] > $user->balance) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient balance. Add funds and try again.'
                ], 422);
            }

            // Prevent self-transfer
            if ($user->profile->account_number === $validated['account_number']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You cannot transfer funds to your own account.'
                ], 422);
            }

            // Find recipient
            $recipientProfile = UserProfile::with('user')
                ->where('account_number', $validated['account_number'])
                ->first();

            if (!$recipientProfile || !$recipientProfile->user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Recipient account not found.'
                ], 404);
            }

            DB::beginTransaction();

            try {

                $transactionId = 'INTER_BANK_' . Str::uuid();
                $description = "Inter Bank Transfer";
                $recipientName = $recipientProfile->user->first_name . ' ' . $recipientProfile->user->last_name;

                // Create transfer record
                $transfer = InterBankTransfer::create([
                    'user_id' => $user->id,
                    'recipient_id' => $recipientProfile->user->id,
                    'transfer_id' => $transactionId,
                    'amount' => $validated['amount'],
                    'acct_name' => $recipientName,
                    'account_number' => $validated['account_number'],
                    'trans_status' => 'approved'
                ]);

                // Record transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'reference_id' => $transactionId,
                    'amount' => $validated['amount'],
                    'bank_name' => config('app.name'),
                    'account_number' => $validated['account_number'],
                    'trans_type' => 'debit',
                    'receiver_name' => $recipientName,
                    'description' => $description,
                    'acct_type' => $recipientProfile->user->acct_type ?? 'savings',
                    'trans_status' => 'approved'
                ]);

                // Update balances
                $user->decrement('balance', $validated['amount']);
                $recipientProfile->user->increment('balance', $validated['amount']);

                // Delete used OTP
                $code->delete();

                DB::commit();

                // Send notifications
                if (config('settings.email_notification')) {
                    // Send confirmation to sender
                    Mail::mailer(config('settings.email_provider'))
                        ->to($user->email)
                        ->send(new InterBankTransferConfirmation($transfer));

                    // Send notification to recipient
                    $recipientProfile->user->notify(new RecipientInterBankTransferNotification($transfer, $recipientProfile->user));

                    // Send notification to admins too
                    Notification::send(
                        User::where('role', 'admin')->get(),
                        new NewInterBankTransferNotification($transfer)
                    );
                }

                return response()->json([
                    'status' => 'success',
                    'message' => "Your transfer of " . number_format($validated['amount'], 2) . " USD has been processed successfully.",
                    'balance' => '$' . number_format($user->balance, 2)
                ]);

            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transfer processing failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
