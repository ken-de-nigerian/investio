<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\FundsManagedConfirmation;
use App\Models\Deposit;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminDepositsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deposits = Deposit::with('user')->latest()->paginate(10)->withQueryString();

        $approved_deposits = Deposit::where('status', 'approved')->sum('amount');
        $pending_deposits = Deposit::where('status', 'pending')->sum('amount');
        $rejected_deposits = Deposit::where('status', 'rejected')->sum('amount');

        return view('admin.deposits.index', [
            'title' => 'Account - Deposits',
            'metrics' => [
                'pending_deposits' => $pending_deposits,
                'approved_deposits' => $approved_deposits,
                'rejected_deposits' => $rejected_deposits,
            ],
            'deposits' => $deposits,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Approve a deposit and notify the user.
     *
     * @param Deposit $deposit
     * @return JsonResponse
     */
    public function approve(Deposit $deposit): JsonResponse
    {
        // Prevent re-approving an already approved deposit
        if ($deposit->status === 'approved') {
            return response()->json([
                'status' => 'error',
                'message' => 'Deposit is already approved.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Increment user balance
            $deposit->user->increment('balance', $deposit->amount);

            // Update deposit status
            $deposit->update(['status' => 'approved']);

            // Log transaction
            $transactionId = 'DEPOSIT_APPROVED_' . Str::uuid();
            Transaction::create([
                'user_id' => $deposit->user->id,
                'reference_id' => $transactionId,
                'amount' => $deposit->amount,
                'bank_name' => config('app.name'),
                'account_number' => $deposit->user->profile?->account_number ?? 'N/A',
                'trans_type' => 'credit',
                'receiver_name' => $deposit->user->first_name . ' ' . $deposit->user->last_name,
                'description' => 'Deposited funds have been added to your account',
                'acct_type' => $deposit->user->profile?->acct_type ?? 'savings',
                'trans_status' => 'approved'
            ]);

            // Send email notification if enabled
            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider', 'default'))
                    ->to($deposit->user->email)
                    ->send(new FundsManagedConfirmation($deposit->user, $deposit->amount, 'deposit'));
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Deposit approved successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve deposit: ' . $e->getMessage(), ['deposit_id' => $deposit->id]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to approve deposit. Please try again.'
            ], 500);
        }
    }

    /**
     * Reject a deposit and notify the user.
     *
     * @param Deposit $deposit
     * @return JsonResponse
     */
    public function reject(Deposit $deposit): JsonResponse
    {
        // Prevent rejecting a non-pending deposit
        if ($deposit->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending deposits can be rejected.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Update deposit status to rejected
            $deposit->update(['status' => 'rejected']);

            // Log transaction
            $transactionId = 'DEPOSIT_REJECTED_' . Str::uuid();
            Transaction::create([
                'user_id' => $deposit->user->id,
                'reference_id' => $transactionId,
                'amount' => $deposit->amount,
                'bank_name' => config('app.name'),
                'account_number' => $deposit->user->profile?->account_number ?? 'N/A',
                'trans_type' => 'credit',
                'receiver_name' => $deposit->user->first_name . ' ' . $deposit->user->last_name,
                'description' => 'Deposit request was rejected',
                'acct_type' => $deposit->user->profile?->acct_type ?? 'savings',
                'trans_status' => 'rejected'
            ]);

            // Send email notification if enabled
            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider', 'default'))
                    ->to($deposit->user->email)
                    ->send(new FundsManagedConfirmation($deposit->user, $deposit->amount, 'deposit_rejected'));
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Deposit rejected successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject deposit: ' . $e->getMessage(), ['deposit_id' => $deposit->id]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reject deposit. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Deposit $deposit)
    {
        return view('admin.deposits.show', [
            'title' => 'Account - Deposits',
            'deposit' => $deposit->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deposit $deposit)
    {
        // Delete the deposit
        $deposit->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Deposit deleted successfully'
        ]);
    }
}
