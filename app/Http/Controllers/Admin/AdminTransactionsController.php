<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionsController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        // Search by user (first name, last name, or email)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%$searchTerm%")
                    ->orWhere('last_name', 'like', "%$searchTerm%")
                    ->orWhere('email', 'like', "%$searchTerm%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$searchTerm%"]);
            });
        }

        // Filter by transaction type (trans_type)
        if ($request->filled('status')) {
            $query->where('trans_type', $request->status);
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        return view('admin.transactions.index', [
            'title' => 'Account - Transactions',
            'transactions' => $transactions,
        ]);
    }
}
