<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Mail\WireTransferConfirmation;
use App\Models\OtpCode;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WireTransfer;
use App\Notifications\NewWireTransferNotification;
use App\Services\Geolocation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Str;
use Throwable;

class WireTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $countriesResponse = Geolocation::handleLocationRequest('countries');
        $countries = $countriesResponse->getData(true)['data'] ?? [];

        $sort = $request->query('sort', '');
        $query = WireTransfer::where('user_id', auth()->user()->id);

        if (in_array($sort, ['approved', 'pending', 'rejected'])) {
            $query->where('trans_status', $sort);
        }

        $transfers = $query->latest()->paginate(10)->appends(['sort' => $sort]);

        return view('user.wire-transfer.index', [
            'title' => 'Account - Wire Transfer',
            'countries' => $countries,
            'transfers' => $transfers,
            'sort' => $sort,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'acct_name' => 'required|string|max:255',
            'account_number' => 'required|digits_between:10,16',
            'bank_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'acct_remarks' => 'required|string',
            'acct_type' => 'required|string|max:255',
            'acct_country' => 'required|string|max:255',
            'acct_swift' => 'required|string|max:255',
            'acct_routing' => 'required|string|max:255',
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

            $request->session()->put('wire-transfer-details', $validated);

            return response()->json([
                'status' => 'success',
                'message' => 'An OTP has been sent to your email. Please confirm to continue.',
                'show_otp_validation_modal' => true
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to initiate wire transfer: ' . $exception->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to initiate wire transfer. Please try again later.',
                'show_otp_validation_modal' => false
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if wire transfer details exist in session
        if (!$request->session()->has('wire-transfer-details')) {
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
            $wire = session()->get('wire-transfer-details' , []);

            // Check balance
            if ($wire['amount'] > $user->balance) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient balance. Add funds and try again.'
                ], 422);
            }

            DB::beginTransaction();

            try {

                $transactionId = 'WIRE_TRANS_' . Str::uuid();
                $description = "Wire Transfer";

                // Create transfer record
                $transfer = WireTransfer::create([
                    'user_id' => $user->id,
                    'reference_id' => $transactionId,
                    'amount' => $wire['amount'],
                    'bank_name' => $wire['bank_name'],
                    'acct_name' => $wire['acct_name'],
                    'account_number' => $wire['account_number'],
                    'trans_type' => 'debit',
                    'acct_remarks' => $wire['acct_remarks'],
                    'acct_type' => $wire['acct_type'],
                    'acct_country' => $wire['acct_country'],
                    'acct_swift' => $wire['acct_swift'],
                    'acct_routing' => $wire['acct_routing'],
                    'trans_status' => 'pending'
                ]);

                // Record transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'reference_id' => $transactionId,
                    'amount' => $wire['amount'],
                    'bank_name' => $wire['bank_name'],
                    'account_number' => $wire['account_number'],
                    'trans_type' => 'debit',
                    'receiver_name' => $wire['acct_name'],
                    'description' => $description,
                    'acct_type' => $wire['acct_type'],
                    'trans_status' => 'pending'
                ]);

                // Update balances
                $user->decrement('balance', $wire['amount']);

                // Delete used OTP
                $code->delete();

                DB::commit();

                // Send notifications
                if (config('settings.email_notification')) {
                    // Send confirmation to sender
                    Mail::mailer(config('settings.email_provider'))
                        ->to($user->email)
                        ->send(new WireTransferConfirmation($transfer));

                    // Send notification to admins too
                    Notification::send(
                        User::where('role', 'admin')->get(),
                        new NewWireTransferNotification($transfer)
                    );
                }

                // Clear session data
                $request->session()->forget('wire-transfer-details');

                return response()->json([
                    'status' => 'success',
                    'message' => "Your transfer of " . number_format($wire['amount'], 2) . " USD has been processed successfully.",
                    'redirect' => route('user.wire.transfer.show', $transfer->id),
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
    public function show(WireTransfer $wire)
    {
        return view('user.wire-transfer.show', [
            'title' => 'Account - Wire Transfer',
            'transfer' => $wire->load('user')
        ]);
    }
}
