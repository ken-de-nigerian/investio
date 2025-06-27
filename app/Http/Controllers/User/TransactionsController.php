<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user_id = auth()->user()->id;

        $query = Transaction::where('user_id', $user_id);

        if (request()->has('sort') && in_array(request()->query('sort'), ['approved', 'pending', 'rejected'])) {
            $query->where('trans_status', request()->query('sort'));
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        return view('user.transactions.index', [
            'title' => 'Account - Transactions',
            'transactions' => $transactions
        ]);
    }
}
