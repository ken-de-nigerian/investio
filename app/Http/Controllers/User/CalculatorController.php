<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class CalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::with('category')
            ->where('is_active', 1)
            ->latest()
            ->orderBy('min_amount', 'ASC')
            ->get();

        return view('user.calculator.index', [
            'title' => 'Account - Calculator',
            'plans' => $plans
        ]);
    }
}
