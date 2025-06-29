<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\LoanRepaymentConfirmation;
use App\Mail\LoanRequestConfirmation;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NewLoanRepaymentNotification;
use App\Notifications\NewLoanRequestNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Str;
use Throwable;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $active = Loan::where('status', 'approved')
            ->where('user_id', Auth::user()->id)
            ->selectRaw('COUNT(*) as total_count, SUM(loan_amount) as total_sum')
            ->first();

        $disbursed = Loan::where('status', 'disbursed')
            ->where('user_id', Auth::user()->id)
            ->selectRaw('COUNT(*) as total_count, SUM(loan_amount) as total_sum')
            ->first();

        return view('user.loan.index', [
            'title' => 'Account - Loans',
            'loans' => Loan::where('user_id', Auth::user()->id)
                ->latest()
                ->paginate(10),
            'active_sum' => $active->total_sum,
            'active_count' => $active->total_count,
            'pending' => Loan::where('status', 'pending')
                ->where('user_id', Auth::user()->id)
                ->count(),
            'disbursed_sum' => $disbursed->total_sum,
            'disbursed_count' => $disbursed->total_count,
            'rejected' => Loan::where('status', 'rejected')
                ->where('user_id', Auth::user()->id)
                ->count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.loan.create', [
            'title' => 'Account - Request Loan',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Please log in.'
            ], 401);
        }

        $validated = $request->validate([
            'loan_amount' => ['required', 'numeric', 'min:' . config('settings.loan.min_amount', 100), 'max:' . config('settings.loan.max_amount', 1000000)],
            'tenure_months' => ['required', 'integer', 'min:1', 'max:' . config('settings.loan.repayment_period', 12)],
            'interest_rate' => ['required', 'numeric', 'min:0', 'max:50', 'decimal:0,2'],
            'title' => 'required|string|max:255',
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
        ]);

        try {

            $user = Auth::user();

            if (!$user->profile || !$user->profile->account_number) {
                return response()->json([
                    'success' => false,
                    'message' => 'User profile or account number not found.'
                ], 422);
            }

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

            // Calculate loan_end_date, casting tenure_months to integer
            $loanEndDate = Carbon::now()->addMonths((int) $validated['tenure_months'])->toDateString();

            // Prepare data for Loan creation
            $loanData = array_merge($validated, [
                'user_id' => $user->id,
                'paid_emi' => 0,
                'status' => 'pending',
                'approved_at' => null,
                'disbursed_at' => null,
                'completed_at' => null,
                'loan_end_date' => $loanEndDate,
                'remarks' => null,
            ]);

            $transactionId = 'LOAN_REQUEST_' . Str::uuid();
            $description = "New Loan Request";

            // Create loan
            $loan = Loan::create($loanData);

            // Record transaction
            Transaction::create([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $validated['loan_amount'],
                'bank_name' => config('app.name'),
                'account_number' => $user->profile->account_number,
                'trans_type' => 'credit',
                'receiver_name' => $user->first_name . ' ' . $user->last_name,
                'description' => $description,
                'acct_type' => $user->profile->acct_type ?? 'savings',
                'trans_status' => 'pending'
            ]);

            // Send notifications
            if (config('settings.email_notification')) {
                // Send confirmation to sender
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new LoanRequestConfirmation($loan));

                // Send notification to admins too
                Notification::send(
                    User::where('role', 'admin')->get(),
                    new NewLoanRequestNotification($loan)
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Loan request submitted successfully!',
                'redirect' => route('user.loan.show', $loan->id)
            ]);
        } catch (Exception $e) {
            Log::error('Loan request failed: ' . $e->getMessage());


            return response()->json([
                'success' => false,
                'message' => 'Loan request failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        return view('user.loan.show', [
            'title' => 'Account - Show Loan',
            'loan' => $loan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'repayment_amount' => 'required|numeric|between:' . $loan->monthly_emi . ',' . $loan->total_payment,
        ], [
            'repayment_amount.required' => 'Please enter your repayment amount.',
            'repayment_amount.numeric' => 'Repayment amount must be a number.',
            'repayment_amount.between' => 'Repayment amount must be between $' . number_format($loan->monthly_emi, 2) . ' and $' . number_format($loan->total_payment, 2) . '.',
        ]);

        $user = Auth::user();

        if ($loan->status === 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Loan status is pending. Please contact the administrator.',
            ]);
        }

        if ($loan->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Loan has been approved but not yet disbursed. Please contact the administrator.',
            ]);
        }

        // Check balance
        if ($validated['repayment_amount'] > $user->balance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient balance. Add funds and try again.'
            ], 422);
        }

        try {

            DB::beginTransaction();

            $paidEmi = $loan->paid_emi;
            $tenureMonths = $loan->tenure_months;
            $now = Carbon::now();

            // Final repayment or full overpayment
            if (
                $request->repayment_amount >= $loan->total_payment ||
                ($paidEmi + 1) >= $tenureMonths
            ) {
                $loan->paid_emi = $tenureMonths;
                $loan->status = 'completed';
                $loan->completed_at = $now;
                $loan->next_due_date = null;
            } else {

                // Partial repayment: increment EMI and next due date
                $loan->paid_emi += 1;

                // Determine base date from priority fields
                $baseDate = $loan->next_due_date
                    ?? $loan->disbursed_at
                    ?? $loan->approved_at
                    ?? $loan->created_at;

                $loan->next_due_date = Carbon::parse($baseDate)->addMonths($loan->paid_emi);
            }

            $loan->save();

            $transactionId = 'LOAN_REPAYMENT_' . Str::uuid();
            $description = "Loan Repayment";

            // Record transaction
            Transaction::create([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $validated['repayment_amount'],
                'bank_name' => config('app.name'),
                'account_number' => $user->profile->account_number,
                'trans_type' => 'debit',
                'receiver_name' => $user->first_name . ' ' . $user->last_name,
                'description' => $description,
                'acct_type' => $user->profile->acct_type ?? 'savings',
                'trans_status' => 'approved'
            ]);

            // Update balances
            $user->decrement('balance', $validated['repayment_amount']);

            DB::commit();

            // Send notifications
            if (config('settings.email_notification')) {
                // Send confirmation to sender
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new LoanRepaymentConfirmation($loan, $validated['repayment_amount']));

                // Send notification to admins too
                Notification::send(
                    User::where('role', 'admin')->get(),
                    new NewLoanRepaymentNotification($loan, $validated['repayment_amount'])
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Repayment recorded successfully.',
                'loan' => $loan,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Loan update failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while processing the repayment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
