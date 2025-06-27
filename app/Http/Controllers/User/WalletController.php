<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\DepositConfirmation;
use App\Models\Deposit;
use App\Models\Goal;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NewDepositNotification;
use App\Services\MarketPricesService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Str;
use Throwable;

class WalletController extends Controller
{
    protected MarketPricesService $marketPrices;

    public function __construct(MarketPricesService $marketPrices)
    {
        $this->marketPrices = $marketPrices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::latest()
            ->where('user_id', auth()->user()->id)
            ->select(['id', 'amount', 'description', 'created_at', 'trans_type', 'trans_status'])
            ->limit(5)
            ->get();

        $goals = Goal::with('category')
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->limit(5)
            ->get();

        $loans = Loan::where('user_id', Auth::user()->id)
            ->latest()
            ->limit(4)
            ->get();

        $incomeStats = $this->getMonthlyChange('credit');
        $expenseStats = $this->getMonthlyChange('debit');

        return view('user.wallet.index', [
            'title' => 'Account - Wallet Dashboard',
            'transactions' => $transactions,
            'goals' => $goals,
            'loans' => $loans,
            'income' => $incomeStats['amount'],
            'incomeChange' => $incomeStats['change'],
            'incomeClass' => $incomeStats['class'],
            'incomeDirection' => $incomeStats['direction'],
            'expense' => $expenseStats['amount'],
            'expenseChange' => $expenseStats['change'],
            'expenseClass' => $expenseStats['class'],
            'expenseDirection' => $expenseStats['direction'],
        ]);
    }

    /**
     * Currency conversion endpoint
     */
    public function convert(Request $request): JsonResponse
    {
        $request->validate([
            'abbreviation' => 'required|string|max:10',
            'amount' => 'required|numeric|min:0',
        ]);

        try {

            $result = $this->marketPrices->convertCurrency(
                $request->input('amount'),
                $request->input('abbreviation'),
                config('settings.currency.code')
            );

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Conversion failed. Invalid currency or amount.',
                ], 400);
            }

            return response()->json($result);
        } catch (Throwable $e) {
            Log::error('Currency conversion error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during conversion. Please try again later.',
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function deposit(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string|max:255',
            'convertedAmount' => 'required|numeric|min:0',
        ]);

        try {

            $amount = $request->input('amount');
            $method = $request->input('method');
            $convertedAmount = $request->input('convertedAmount');

            $gateway = $this->marketPrices->getSingleGateway($method);
            if (!$gateway) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid payment method'
                ]);
            }

            if ($amount < $gateway['min_amount'] || $amount > $gateway['max_amount']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Amount must be between ' . number_format($gateway['min_amount'], 2) .
                        ' and ' . number_format($gateway['max_amount'], 2) . ' USD'
                ]);
            }

            $transactionId = 'DEPO_' . Str::uuid();
            $description = "New Deposit Request";

            DB::beginTransaction();

            $deposit = Deposit::create([
                'user_id' => auth()->id(),
                'transaction_id' => $transactionId,
                'payment_method' => $method,
                'amount' => $amount,
                'converted_amount' => $convertedAmount,
                'status' => 'pending',
                'description' => $description,
            ]);

            Transaction::create([
                'user_id' => auth()->id(),
                'reference_id' => $transactionId,
                'amount' => $amount,
                'bank_name' => $gateway['name'],
                'account_number' => auth()->user()->profile->account_number,
                'trans_type' => 'credit',
                'receiver_name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                'description' => $description,
                'acct_type' => auth()->user()->profile->acct_type ?? 'savings',
                'trans_status' => 'pending'
            ]);

            DB::commit();

            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))->to(auth()->user()->email)->send(new DepositConfirmation($deposit));
                Notification::send(User::where('role', 'admin')->get(), new NewDepositNotification($deposit));
            }

            return response()->json([
                'status' => 'success',
                'message' => "Your deposit request of " . number_format($amount, 2) . " USD has been successfully processed.",
                'balance' => '$' . number_format(auth()->user()->balance, 2)
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Deposit processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @param $type
     * @return array
     */
    private function getMonthlyChange($type)
    {
        $now = now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $lastMonth = $now->copy()->subMonth();
        $lastMonthNumber = $lastMonth->month;
        $lastMonthYear = $lastMonth->year;

        $current = Transaction::where('trans_type', $type)
            ->where('user_id', auth()->user()->id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('amount');

        $previous = Transaction::where('trans_type', $type)
            ->where('user_id', auth()->user()->id)
            ->whereMonth('created_at', $lastMonthNumber)
            ->whereYear('created_at', $lastMonthYear)
            ->sum('amount');

        $change = $previous == 0
            ? ($current == 0 ? 0 : 100)
            : round((($current - $previous) / $previous) * 100, 2);

        return [
            'amount' => $current,
            'change' => $change,
            'direction' => $change >= 0 ? 'up' : 'down',
            'class' => $change >= 0 ? 'text-success' : 'text-danger',
        ];
    }

    /**
     * @return JsonResponse
     */
    public function getDailyIncomeExpense()
    {
        $days = 30;
        $data = Transaction::selectRaw("DATE(created_at) as date, trans_type, SUM(amount) as total")
            ->where('user_id', auth()->user()->id)
            ->whereBetween('created_at', [now()->subDays($days), now()])
            ->groupBy('date', 'trans_type')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        $labels = [];
        $income = [];
        $expense = [];

        foreach (range(0, $days - 1) as $i) {
            $date = now()->subDays($days - 1 - $i)->format('Y-m-d');
            $labels[] = $date;
            $daily = $data->get($date, collect());

            $income[] = $daily->where('trans_type', 'credit')->sum('total');
            $expense[] = $daily->where('trans_type', 'debit')->sum('total');
        }

        return response()->json([
            'labels' => $labels,
            'income' => $income,
            'expense' => $expense,
        ]);
    }
}
