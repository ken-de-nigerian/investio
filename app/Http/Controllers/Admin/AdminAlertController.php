<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\FundsManagedConfirmation;
use App\Models\Alert;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->latest()->get();

        // Get the sort parameter from the request
        $sort = request()->query('sort', '');

        // Build the query for alerts
        $query = Alert::with('user')->latest();

        // Apply sorting if specified
        if (in_array($sort, ['credit', 'debit'])) {
            $query->where('trans_type', $sort);
        }

        $alerts = $query->paginate(10)->withQueryString();

        return view('admin.alert.create', [
            'title' => 'Account - Alerts',
            'users' => $users,
            'alerts' => $alerts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|min:2|max:255',
            'sender_bank' => 'required|string|min:2|max:255',
            'receiver' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'trans_type' => 'required|in:credit,debit',
            'status' => 'required|in:approved,pending,rejected',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {

            $user = User::findOrFail($validated['receiver']);

            $amount = $validated['amount'];
            $type = $validated['trans_type'];

            // Determine transaction nature
            $transType = $type === 'credit' ? 'deposit' : 'withdraw';

            // Determine transaction description
            $description = $type === 'credit'
                ? 'Funds credited to your account'
                : 'Funds withdrawn from your account';

            // Check withdrawal limit
            if ($type === 'debit' && $user->balance < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance, amount is higher than the user\'s balance'
                ], 422);
            }

            // Only update balance if transaction is approved
            if ($validated['status'] === 'approved') {
                // Update balance
                $user->balance = $type === 'credit'
                    ? $user->balance + $amount
                    : $user->balance - $amount;

                $user->save();
            }

            // Create alert
            Alert::create([
                'user_id' => $validated['receiver'],
                'sender_name' => $validated['sender_name'],
                'sender_bank' => $validated['sender_bank'],
                'amount' => $validated['amount'],
                'trans_type' => $validated['trans_type'],
                'status' => $validated['status'],
                'date' => $validated['date'],
            ]);

            // Log transaction
            $transactionId = strtoupper($type) . '_' . Str::uuid();
            Transaction::create([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $amount,
                'bank_name' => config('app.name'),
                'account_number' => $user->profile->account_number,
                'trans_type' => $type,
                'receiver_name' => $user->full_name ?? $user->first_name . ' ' . $user->last_name,
                'description' => $description,
                'acct_type' => $user->profile->acct_type ?? 'savings',
                'trans_status' => 'approved'
            ]);

            // Send notifications
            if (config('settings.email_notification')) {
                // Send confirmation to the user
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new FundsManagedConfirmation($user, $amount, $transType));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Alert recorded successfully!',
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while recording the alert.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alert $alert)
    {
        $users = User::where('role', '!=', 'admin')->latest()->get();

        return view('admin.alert.edit', [
            'title' => 'Account - Deposits',
            'alert' => $alert,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alert $alert)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|min:2|max:255',
            'sender_bank' => 'required|string|min:2|max:255',
            'receiver' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'trans_type' => 'required|in:credit,debit',
            'status' => 'required|in:approved,pending,rejected',
            'date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {

            $user = User::findOrFail($validated['receiver']);
            $amount = $validated['amount'];
            $type = $validated['trans_type'];

            // Determine transaction nature
            $transType = $type === 'credit' ? 'deposit' : 'withdraw';

            // Determine transaction description
            $description = $type === 'credit'
                ? 'Funds credited to your account'
                : 'Funds withdrawn from your account';

            // Reverse previous balance update if the original alert was approved
            if ($alert->status === 'approved') {
                $previousAmount = $alert->amount;
                $previousType = $alert->trans_type;
                $user->balance = $previousType === 'credit'
                    ? $user->balance - $previousAmount
                    : $user->balance + $previousAmount;
            }

            // Apply new balance update if the new status is approved
            if ($validated['status'] === 'approved') {
                // Check withdrawal limit for debit
                if ($type === 'debit' && $user->balance < $amount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient balance, amount is higher than the user\'s balance'
                    ], 422);
                }

                $user->balance = $type === 'credit'
                    ? $user->balance + $amount
                    : $user->balance - $amount;

                $user->save();
            }

            // Update alert
            $alert->update([
                'user_id' => $validated['receiver'],
                'sender_name' => $validated['sender_name'],
                'sender_bank' => $validated['sender_bank'],
                'amount' => $validated['amount'],
                'trans_type' => $validated['trans_type'],
                'status' => $validated['status'],
                'date' => $validated['date'],
            ]);

            // Log new transaction if status is approved
            if ($validated['status'] === 'approved') {
                $transactionId = strtoupper($type) . '_' . Str::uuid();
                Transaction::create([
                    'user_id' => $user->id,
                    'reference_id' => $transactionId,
                    'amount' => $amount,
                    'bank_name' => config('app.name'),
                    'account_number' => $user->profile->account_number,
                    'trans_type' => $type,
                    'receiver_name' => $user->full_name ?? $user->first_name . ' ' . $user->last_name,
                    'description' => $description,
                    'acct_type' => $user->profile->acct_type ?? 'savings',
                    'trans_status' => $validated['status']
                ]);

                // Send notifications
                if (config('settings.email_notification')) {
                    Mail::mailer(config('settings.email_provider'))
                        ->to($user->email)
                        ->send(new FundsManagedConfirmation($user, $amount, $transType));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Alert updated successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the alert.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alert $alert)
    {
        // Delete the alert
        $alert->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Alert deleted successfully'
        ]);
    }
}
