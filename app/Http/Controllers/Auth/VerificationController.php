<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OtpCode;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $request->validate([
                'otp' => ['required', 'digits:4']
            ]);

            $code = OtpCode::where('user_id', auth()->id())
                ->where('code', $request->input('otp'))
                ->first();

            if (!$code) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP. Please try again.'
                ]);
            }

            if (Carbon::now()->greaterThan($code->expires_at)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP has expired. Request a new one.'
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully.'
            ]);
        } catch (Exception $exception) {
            Log::error('Failed to verify OTP: ' . $exception->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        try {

            $user = auth()->user();

            $otp = random_int(1000, 9999);
            $otpExpiry = Carbon::now()->addMinutes(5);

            OtpCode::updateOrCreate(
                ['user_id' => $user->id],
                ['code' => $otp, 'expires_at' => $otpExpiry]
            );

            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))->to($user->email)->send(new OtpMail($otp, $user));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'A new OTP has been sent to your email.',
                'otpExpiry' => $otpExpiry
            ]);
        }catch (Exception $exception){
            Log::error('Failed to resend OTP: ' . $exception->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
