<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReferralsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('user.referrals.index', [
            'title' => 'Account - Referrals',
            'metrics' => [
                'total_registrations' => User::where('ref_by', $user->id)->count(),
                'referral_earnings' => Referral::where('to_id', $user->id)->sum('amount'),
            ]
        ]);
    }
}
