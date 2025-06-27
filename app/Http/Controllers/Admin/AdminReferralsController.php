<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;

class AdminReferralsController extends Controller
{
    public function index()
    {
        return view('admin.referrals.index', [
            'title' => 'Account - Referrals & Commissions',
            'metrics' => [
                'total_registrations' => User::whereNotNull('ref_by')->count(),
                'referral_earnings' => Referral::sum('amount'),
            ],
            'referredUsers' => User::with('referrer')
                ->whereNotNull('ref_by')
                ->latest()
                ->get(),
            'referralCommissions' => Referral::with('referredUser', 'referrer', 'investment.plan')
                ->latest()
                ->paginate('10')
        ]);
    }
}
