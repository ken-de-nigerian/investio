<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\LoanManagedConfirmation;
use App\Models\Loan;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->query('sort', '');
        $query = Loan::with('user');

        if (in_array($sort, ['pending', 'approved', 'rejected', 'disbursed', 'completed'])) {
            $query->where('status', $sort);
        }

        $loans = $query->latest()->paginate(10)->appends(['sort' => $sort]);

        $active = Loan::where('status', 'approved')
            ->selectRaw('COUNT(*) as total_count, SUM(loan_amount) as total_sum')
            ->first();

        $disbursed = Loan::where('status', 'disbursed')
            ->selectRaw('COUNT(*) as total_count, SUM(loan_amount) as total_sum')
            ->first();

        return view('admin.loans.index', [
            'title' => 'Account - Loans',
            'loans' => $loans,
            'sort' => $sort,
            'active_sum' => $active->total_sum,
            'active_count' => $active->total_count,
            'pending' => Loan::where('status', 'pending')->count(),
            'disbursed_sum' => $disbursed->total_sum,
            'disbursed_count' => $disbursed->total_count,
            'rejected' => Loan::where('status', 'rejected')->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function edit(Loan $loan)
    {
        return view('admin.loans.edit', [
            'title' => 'Account - Request Loan',
            'loan' => $loan,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        return view('admin.loans.show', [
            'title' => 'Account - Show Loan',
            'loan' => $loan,
        ]);
    }

    /**
     * Update an existing loan resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'loan_amount' => ['required', 'numeric', 'min:' . config('settings.loan.min_amount', 100), 'max:' . config('settings.loan.max_amount', 1000000)],
            'tenure_months' => ['required', 'integer', 'min:1', 'max:' . config('settings.loan.repayment_period', 12)],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:50', 'decimal:0,2'],
            'title' => 'required|string|max:255',
            'status' => 'required|string|in:pending,approved,rejected,disbursed,completed',
            'loan_reason' => 'required|string|max:1000',
            'loan_collateral' => 'required|string|max:1000',
            'monthly_emi' => 'required|numeric|min:0',
            'total_interest' => 'required|numeric|min:0',
            'total_payment' => 'required|numeric|min:0',
        ], [
            'loan_amount.min' => 'The loan amount must be at least $' . config('settings.loan.min_amount', 100) . '.',
            'loan_amount.max' => 'The loan amount cannot exceed $' . config('settings.loan.max_amount', 1000000) . '.',
            'tenure_months.max' => 'The loan tenure cannot exceed ' . config('settings.loan.repayment_period', 12) . ' months.',
            'interest_rate.decimal' => 'The interest rate must have up to 2 decimal places.',
            'status.in' => 'The selected status is invalid.',
        ]);

        try {

            // Store original status for comparison
            $originalStatus = $loan->status;

            // Recalculate EMI to verify values
            $principal = $validated['loan_amount'];
            $rate = $validated['interest_rate'] / 100 / 12;
            $tenure = $validated['tenure_months'];
            $emi = ($principal * $rate * pow(1 + $rate, $tenure)) / (pow(1 + $rate, $tenure) - 1);
            $totalPayment = $emi * $tenure;
            $totalInterest = $totalPayment - $principal;

            // Allow small floating-point differences
            $tolerance = 0.01;
            if (
                abs($emi - $validated['monthly_emi']) > $tolerance ||
                abs($totalInterest - $validated['total_interest']) > $tolerance ||
                abs($totalPayment - $validated['total_payment']) > $tolerance
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Calculated values do not match submitted values. Please try again.'
                ], 422);
            }

            // Calculate loan_end_date based on updated tenure
            $loanEndDate = Carbon::now()->addMonths((int) $validated['tenure_months'])->toDateString();

            // Prepare update data
            $updateData = array_merge($validated, [
                'loan_end_date' => $loanEndDate,
            ]);

            // Set status-specific timestamps
            switch ($validated['status']) {
                case 'approved':
                    if ($originalStatus !== 'approved') {
                        $updateData['approved_at'] = Carbon::now();
                    }
                    break;
                case 'disbursed':
                    if ($originalStatus !== 'disbursed') {
                        $updateData['disbursed_at'] = Carbon::now();
                        // If not already approved, set approved_at as well
                        if (is_null($loan->approved_at)) {
                            $updateData['approved_at'] = Carbon::now();
                        }
                    }
                    break;
                case 'completed':
                    if ($originalStatus !== 'completed') {
                        $updateData['completed_at'] = Carbon::now();
                    }
                    break;
                case 'rejected':
                    // Clear approval/disbursement timestamps if rejected
                    $updateData['approved_at'] = null;
                    $updateData['disbursed_at'] = null;
                    $updateData['completed_at'] = null;
                    break;
                case 'pending':
                    // Clear all status timestamps if back to pending
                    $updateData['approved_at'] = null;
                    $updateData['disbursed_at'] = null;
                    $updateData['completed_at'] = null;
                    break;
            }

            // Update the loan
            $loan->update($updateData);

            // Refresh the loan model to get updated timestamps
            $loan->refresh();

            // Handle status change notifications
            if ($originalStatus !== $validated['status'] && config('settings.email_notification')) {
                $this->sendStatusChangeNotification($loan, $originalStatus, $validated['status']);
            }

            // Update or create a transaction record based on status
            if ($validated['status'] === 'disbursed' && $originalStatus !== 'disbursed') {
                $this->createDisbursementTransaction($loan);
            }

            return response()->json([
                'success' => true,
                'message' => 'Loan updated successfully!',
                'data' => [
                    'loan' => $loan->fresh(),
                    'status_changed' => $originalStatus !== $validated['status']
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Loan update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Loan update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send email notification based on a status change
     */
    private function sendStatusChangeNotification(Loan $loan, string $originalStatus, string $newStatus)
    {
        try {
            $user = $loan->user;

            // Only send email if user exists and has an email
            if (!$user || !$user->email) {
                return;
            }

            // Ensure all required timestamp fields are set before sending email
            $shouldSendEmail = false;

            switch ($newStatus) {
                case 'approved':
                    if ($originalStatus !== 'approved' && $loan->approved_at) {
                        $shouldSendEmail = true;
                    }
                    break;

                case 'disbursed':
                    if ($originalStatus !== 'disbursed' && $loan->disbursed_at) {
                        $shouldSendEmail = true;
                    }
                    break;

                case 'rejected':
                    if ($originalStatus !== 'rejected') {
                        $shouldSendEmail = true;
                    }
                    break;

                case 'completed':
                    if ($originalStatus !== 'completed' && $loan->completed_at) {
                        $shouldSendEmail = true;
                    }
                    break;
            }

            if ($shouldSendEmail) {
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new LoanManagedConfirmation($loan));
            }

        } catch (Exception $e) {
            Log::warning('Failed to send loan status notification email', [
                'loan_id' => $loan->id,
                'user_email' => $user->email,
                'status' => $newStatus,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create disbursement transaction record
     */
    private function createDisbursementTransaction(Loan $loan)
    {
        try {

            $user = $loan->user;

            if (!$user) {
                return;
            }

            if ($loan->status === 'disbursed') {
                $user->increment('balance', $loan->loan_amount);
            }

            $transactionId = 'LOAN_DISBURSEMENT_' . $loan->id . '_' . Str::uuid();
            $description = "Loan Disbursement - $loan->title";

            Transaction::create([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $loan->loan_amount,
                'bank_name' => config('app.name'),
                'account_number' => $user->profile->account_number ?? 'N/A',
                'trans_type' => 'credit',
                'receiver_name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                'description' => $description,
                'acct_type' => $user->profile->acct_type ?? 'savings',
                'trans_status' => 'approved'
            ]);
        } catch (Exception $e) {
            Log::error('Failed to create disbursement transaction', [
                'loan_id' => $loan->id,
                'user_id' => $loan->user_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        // Delete the loan
        $loan->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Loan deleted successfully'
        ]);
    }
}
