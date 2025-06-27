<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\KycApprovedConfirmation;
use App\Mail\KycRejectedConfirmation;
use App\Models\Kyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminKycController extends Controller
{
    public function index()
    {
        return view('admin.kyc.index', [
            'title' => 'Account - KYC Verifications',
            'metrics' => [
                'kyc_unverified' => Kyc::where('status', 'pending')->count(),
                'kyc_rejected' => Kyc::where('status', 'rejected')->count(),
            ],
            'kycs' => Kyc::with('user')
                ->where('status', 'pending')
                ->latest()->paginate('10'),
        ]);
    }

    public function approve(Kyc $kyc)
    {
        $kyc->status = 'approved';
        $kyc->approved_at = now();
        $kyc->rejection_reason = null;
        $kyc->save();

        // Send email
        if (config('settings.email_notification')) {
            Mail::mailer(config('settings.email_provider'))
                ->to($kyc->user->email)
                ->send(new KycApprovedConfirmation($kyc));
        }

        return back()->with('success', 'KYC approved successfully.');
    }

    public function reject(Request $request, Kyc $kyc)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $kyc->status = 'rejected';
        $kyc->rejection_reason = $request->rejection_reason;
        $kyc->save();

        // Send email
        if (config('settings.email_notification')) {
            Mail::mailer(config('settings.email_provider'))
                ->to($kyc->user->email)
                ->send(new KycRejectedConfirmation($kyc));
        }

        return back()->with('success', 'KYC rejected successfully.');
    }

    public function modal(Kyc $kyc)
    {
        return view('admin.kyc._modal', [
            'kyc' => $kyc
        ]);
    }
}
