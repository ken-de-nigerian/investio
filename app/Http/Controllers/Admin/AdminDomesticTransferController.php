<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DomesticTransferManagedConfirmation;
use App\Models\DomesticTransfer;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Str;

class AdminDomesticTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', '');
        $query = DomesticTransfer::with('user');

        if (in_array($sort, ['approved', 'pending', 'rejected'])) {
            $query->where('trans_status', $sort);
        }

        $transfers = $query->latest()->paginate(10)->appends(['sort' => $sort]);

        $approved_transfers = DomesticTransfer::where('trans_status', 'approved')->sum('amount');
        $pending_transfers = DomesticTransfer::where('trans_status', 'pending')->sum('amount');
        $rejected_transfers = DomesticTransfer::where('trans_status', 'rejected')->sum('amount');

        return view('admin.domestic.index', [
            'title' => 'Account - Domestic Transfers',
            'metrics' => [
                'pending_transfers' => $pending_transfers,
                'approved_transfers' => $approved_transfers,
                'rejected_transfers' => $rejected_transfers,
            ],
            'transfers' => $transfers,
            'sort' => $sort,
        ]);
    }

    /**
     * Renders a domestic transfer form
     */
    public function create()
    {
        $users = User::where('role', '!=', 'admin')->latest()->get();

        return view('admin.domestic.create', [
            'title' => 'Account - Domestic Transfers',
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender' => 'required|exists:users,id',
            'acct_name' => 'required|string|min:2|max:255',
            'account_number' => 'required|string|min:2|max:255',
            'amount' => 'required|numeric|min:0.01',
            'bank_name' => 'required|string|min:2|max:255',
            'acct_type' => 'required|string|min:2|max:255',
            'trans_status' => 'required|in:approved,pending,rejected',
            'date' => 'required|date_format:Y-m-d',
            'acct_remarks' => 'required|string|min:2|max:255',
        ]);

        DB::beginTransaction();

        try {

            $user = User::findOrFail($validated['sender']);
            $transactionId = 'DOMESTIC_TRANS_' . Str::uuid();

            // Check withdrawal limit
            if ($user->balance < $validated['amount']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance, amount is higher than the user\'s balance'
                ], 422);
            }

            // Only decrement balance if transaction is approved
            if ($validated['trans_status'] === 'approved') {
                $user->decrement('balance', $validated['amount']);
            }

            // Convert date to a Carbon instance for created_at
            $createdAt = Carbon::createFromFormat('Y-m-d', $validated['date'])->startOfDay();

            // Create transfer record with custom created_at
            $transfer = new DomesticTransfer([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $validated['amount'],
                'bank_name' => $validated['bank_name'],
                'acct_name' => $validated['acct_name'],
                'account_number' => $validated['account_number'],
                'trans_type' => 'debit',
                'acct_remarks' => $validated['acct_remarks'],
                'acct_type' => $validated['acct_type'],
                'trans_status' => $validated['trans_status'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $transfer->timestamps = false;
            $transfer->save();

            // Set description based on transaction status
            $description = $validated['trans_status'] === 'pending'
                ? 'Domestic Transfer'
                : 'Domestic Transfer ' . ucfirst($validated['trans_status']);

            // Record transaction
            Transaction::create([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $validated['amount'],
                'bank_name' => $validated['bank_name'],
                'account_number' => $validated['account_number'],
                'trans_type' => 'debit',
                'receiver_name' => $validated['acct_name'],
                'description' => $description,
                'acct_type' => $validated['acct_type'],
                'trans_status' => $validated['trans_status']
            ]);

            // Send notifications for approved or rejected transfers
            if (in_array($validated['trans_status'], ['approved', 'rejected']) && config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new DomesticTransferManagedConfirmation($transfer));
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transfer recorded successfully!',
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while recording the alert.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DomesticTransfer $domestic)
    {
        $users = User::where('role', '!=', 'admin')->latest()->get();

        return view('admin.domestic.edit', [
            'title' => 'Account - Domestic Transfers',
            'transfer' => $domestic,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DomesticTransfer $domestic)
    {
        $validated = $request->validate([
            'sender' => 'required|exists:users,id',
            'acct_name' => 'required|string|min:2|max:255',
            'account_number' => 'required|string|min:2|max:255',
            'amount' => 'required|numeric|min:0.01',
            'bank_name' => 'required|string|min:2|max:255',
            'acct_type' => 'required|string|min:2|max:255',
            'trans_status' => 'required|in:approved,pending,rejected',
            'date' => 'required|date_format:Y-m-d',
            'acct_remarks' => 'required|string|min:2|max:255',
        ]);

        DB::beginTransaction();

        try {

            $user = User::findOrFail($validated['sender']);
            $oldUser = User::findOrFail($domestic->user_id);

            // Store original values for comparison
            $originalAmount = $domestic->amount;
            $originalStatus = $domestic->trans_status;

            // Handle balance adjustments based on status changes
            $this->handleBalanceUpdates($oldUser, $user, $originalAmount, $validated['amount'], $originalStatus, $validated['trans_status']);

            // Convert date to a Carbon instance
            $updatedAt = Carbon::createFromFormat('Y-m-d', $validated['date'])->startOfDay();

            // Update transfer record
            $domestic->update([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'bank_name' => $validated['bank_name'],
                'acct_name' => $validated['acct_name'],
                'account_number' => $validated['account_number'],
                'acct_remarks' => $validated['acct_remarks'],
                'acct_type' => $validated['acct_type'],
                'trans_status' => $validated['trans_status'],
                'created_at' => $updatedAt,
                'updated_at' => now(),
            ]);

            // Set description based on transaction status
            $description = $validated['trans_status'] === 'pending'
                ? 'Domestic Transfer'
                : 'Domestic Transfer ' . ucfirst($validated['trans_status']);

            // Update the corresponding transaction record
            $transaction = Transaction::where('reference_id', $domestic->reference_id)->first();
            if ($transaction) {
                $transaction->update([
                    'user_id' => $user->id,
                    'amount' => $validated['amount'],
                    'bank_name' => $validated['bank_name'],
                    'account_number' => $validated['account_number'],
                    'receiver_name' => $validated['acct_name'],
                    'description' => $description,
                    'acct_type' => $validated['acct_type'],
                    'trans_status' => $validated['trans_status']
                ]);
            }

            // Send notifications for status changes to approved or rejected
            if ($this->shouldSendNotification($originalStatus, $validated['trans_status']) && config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new DomesticTransferManagedConfirmation($domestic));
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transfer updated successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the transfer.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle balance updates when the transfer is modified
     * @throws Exception
     */
    private function handleBalanceUpdates($oldUser, $newUser, $originalAmount, $newAmount, $originalStatus, $newStatus)
    {
        // If the original transaction was approved, we need to reverse it first
        if ($originalStatus === 'approved') {
            // Add back the original amount to the old user's balance
            $oldUser->increment('balance', $originalAmount);
        }

        // If the new status is approved, deduct the new amount
        if ($newStatus === 'approved') {
            // Check if the new user has sufficient balance
            if ($newUser->balance < $newAmount) {
                throw new Exception('Insufficient balance, amount is higher than the user\'s balance');
            }

            // Deduct the new amount from the new user's balance
            $newUser->decrement('balance', $newAmount);
        }
    }

    /**
     * Determine if notification should be sent based on the status change
     */
    private function shouldSendNotification($originalStatus, $newStatus)
    {
        return in_array($newStatus, ['approved', 'rejected']) &&
            ($originalStatus !== $newStatus || $originalStatus === 'pending');
    }

    /**
     * Display the specified resource.
     */
    public function show(DomesticTransfer $domestic)
    {
        return view('admin.domestic.show', [
            'title' => 'Account - Domestic Transfers',
            'transfer' => $domestic->load('user'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DomesticTransfer $domestic)
    {
        // Delete the transfer
        $domestic->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Domestic transfer deleted successfully'
        ]);
    }
}
