<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\DomesticTransferConfirmation;
use App\Mail\OtpMail;
use App\Models\DomesticTransfer;
use App\Models\OtpCode;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NewDomesticTransferNotification;
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

class DomesticTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', '');
        $query = DomesticTransfer::where('user_id', auth()->user()->id);

        if (in_array($sort, ['approved', 'pending', 'rejected'])) {
            $query->where('trans_status', $sort);
        }

        $transfers = $query->latest()->paginate(10)->appends(['sort' => $sort]);

        return view('user.domestic-transfer.index', [
            'title' => 'Account - Domestic Transfer',
            'transfers' => $transfers,
            'sort' => $sort,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'acct_name' => 'required|string|max:255',
            'account_number' => 'required|digits_between:10,16',
            'bank_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'acct_remarks' => 'required|string',
            'acct_type' => 'required|string|max:255'
        ]);

        try {

            $otp = random_int(1000, 9999);
            $otpExpiry = Carbon::now()->addMinutes(5);

            OtpCode::updateOrCreate(
                ['user_id' => auth()->user()->id],
                ['code' => $otp, 'expires_at' => $otpExpiry]
            );

            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))->to(auth()->user()->email)->send(new OtpMail($otp, auth()->user()));
            }

            $request->session()->put('domestic-transfer-details', $validated);

            return response()->json([
                'status' => 'success',
                'message' => 'An OTP has been sent to your email. Please confirm to continue.',
                'show_otp_validation_modal' => true
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to initiate domestic transfer: ' . $exception->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to initiate domestic transfer. Please try again later.',
                'show_otp_validation_modal' => false
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if domestic transfer details exist in session
        if (!$request->session()->has('domestic-transfer-details')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session has expired. Please refresh the page and try again.',
            ]);
        }

        $validated = $request->validate([
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

            // Get transfer details
            $domestic = session()->get('domestic-transfer-details' , []);

            // Check balance
            if ($domestic['amount'] > $user->balance) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient balance. Add funds and try again.'
                ], 422);
            }

            DB::beginTransaction();

            try {

                $transactionId = 'DOMESTIC_TRANS_' . Str::uuid();
                $description = "Domestic Transfer";

                // Create transfer record
                $transfer = DomesticTransfer::create([
                    'user_id' => $user->id,
                    'reference_id' => $transactionId,
                    'amount' => $domestic['amount'],
                    'bank_name' => $domestic['bank_name'],
                    'acct_name' => $domestic['acct_name'],
                    'account_number' => $domestic['account_number'],
                    'trans_type' => 'debit',
                    'acct_remarks' => $domestic['acct_remarks'],
                    'acct_type' => $domestic['acct_type'],
                    'trans_status' => 'pending'
                ]);

                // Record transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'reference_id' => $transactionId,
                    'amount' => $domestic['amount'],
                    'bank_name' => $domestic['bank_name'],
                    'account_number' => $domestic['account_number'],
                    'trans_type' => 'debit',
                    'receiver_name' => $domestic['acct_name'],
                    'description' => $description,
                    'acct_type' => $domestic['acct_type'],
                    'trans_status' => 'pending'
                ]);

                // Update balances
                $user->decrement('balance', $domestic['amount']);

                // Delete used OTP
                $code->delete();

                DB::commit();

                // Send notifications
                if (config('settings.email_notification')) {
                    // Send confirmation to sender
                    Mail::mailer(config('settings.email_provider'))
                        ->to($user->email)
                        ->send(new DomesticTransferConfirmation($transfer));

                    // Send notification to admins too
                    Notification::send(
                        User::where('role', 'admin')->get(),
                        new NewDomesticTransferNotification($transfer)
                    );
                }

                // Clear session data
                $request->session()->forget('domestic-transfer-details');

                return response()->json([
                    'status' => 'success',
                    'message' => "Your transfer of " . number_format($domestic['amount'], 2) . " USD has been processed successfully.",
                    'redirect' => route('user.domestic.transfer.show', $transfer->id),
                    'balance' => '$' . number_format($user->balance, 2)
                ]);

            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (Throwable $e) {
            Log::error('Transfer processing failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Transfer processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DomesticTransfer $domestic)
    {
        return view('user.domestic-transfer.show', [
            'title' => 'Account - Domestic Transfer',
            'transfer' => $domestic->load('user')
        ]);
    }
}
