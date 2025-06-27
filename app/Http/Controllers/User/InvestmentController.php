<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\InvestmentLiquidatedConfirmation;
use App\Mail\InvestmentPurchaseConfirmation;
use App\Mail\ReferralCommissionConfirmation;
use App\Models\Plan;
use App\Models\PlanCategory;
use App\Models\Referral;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInvestment;
use App\Notifications\NewInvestmentLiquidatedNotification;
use App\Notifications\NewInvestmentPurchaseNotification;
use App\Notifications\NewReferralCommissionNotification;
use App\Services\MarketPricesService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Str;
use Throwable;

class InvestmentController extends Controller
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
        $plan_categories =  PlanCategory::where('is_active', 1)
            ->orderBy('name')
            ->get();

        $plans = Plan::with('category')
            ->where('is_active', 1)
            ->latest()
            ->orderBy('min_amount', 'ASC')
            ->limit(6)
            ->get();

        $investments = UserInvestment::with('plan', 'plan.category')
            ->where('user_id', Auth::id())
            ->where('status', 'running')
            ->latest()
            ->limit(3)
            ->get();

        return view('user.investment.index', [
            'title' => 'Account - Investments',
            'plan_categories' => $plan_categories,
            'plans' => $plans,
            'investments' => $investments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function plans(Request $request)
    {
        $plan_categories =  PlanCategory::where('is_active', 1)
            ->orderBy('name')
            ->get();

        // Get the search query from the request
        $search = $request->query('search', '');

        // Build the query for plans
        $plans = Plan::with('category')
            ->where('is_active', 1)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('returns', 'like', "%$search%")
                        ->orWhereHas('category', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        });
                });
            })
            ->orderBy('min_amount', 'ASC')
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('user.investment.plans', [
            'title' => 'Account - Investment Plans',
            'plan_categories' => $plan_categories,
            'plans' => $plans,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function categories(Request $request, string $slug)
    {
        // Get all categories
        $category = PlanCategory::where('slug', $slug)
            ->where('is_active', 1)->firstOrfail();

        // Get the search query from the request
        $search = $request->query('search', '');

        // Build the query for plans
        $plans = Plan::with('category')
            ->where('plan_category_id', $category->id)
            ->where('is_active', 1)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('returns', 'like', "%$search%");
                });
            })
            ->orderBy('min_amount', 'ASC')
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('user.investment.categories', [
            'title' => 'Account - Investment Categories',
            'category' => $category,
            'plans' => $plans,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Clean and validate the data
        $validatedData = $request->validate([
            'investment_amount' => 'required|numeric|min:1',
            'agree_terms' => 'required|accepted',
            'lock_funds' => 'required|accepted',
            'plan_id' => 'required|exists:plans,id',
            'expected_profit' => 'required|string',
            'end_date' => 'required|string',
        ]);

        $user = Auth::user();

        // Get the plan
        $plan = Plan::findOrFail($validatedData['plan_id']);

        // Validate minimum investment amount against plan
        if ($validatedData['investment_amount'] < $plan->min_amount) {
            return response()->json([
                'success' => false,
                'message' => "Minimum investment amount is $" . number_format($plan->min_amount, 2)
            ], 422);
        }

        // Check balance
        if ($validatedData['investment_amount'] > $user->balance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance. Add funds and try again.'
            ], 422);
        }

        // Clean the expected profit amount (remove $ and convert to float)
        $expectedProfitCleaned = (float) preg_replace('/[^\d.]/', '', $validatedData['expected_profit']);

        // Parse the end date
        try {
            $endDate = Carbon::createFromFormat('F j, Y', $validatedData['end_date']);
        } catch (Exception) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid end date format'
            ], 422);
        }

        // Calculate the actual projected return using the plan's method
        $actualProjectedReturn = $plan->getProjectedReturnAttribute($validatedData['investment_amount']);

        // Check if the received value is lower than expected (prevent manipulation)
        if (round($expectedProfitCleaned) < round($actualProjectedReturn)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid projected return calculation. Please refresh and try again.',
                'expected' => $actualProjectedReturn,
                'received' => $expectedProfitCleaned
            ], 422);
        }

        // Calculate actual end date based on plan duration
        $calculatedEndDate = Carbon::now()->addDays($plan->duration_days);

        // Verify end date (allow 1 day tolerance)
        if (abs($endDate->diffInDays($calculatedEndDate)) > 1) {
            return response()->json([
                'success' => false,
                'message' => 'Investment end date mismatch. Please refresh and try again.'
            ], 422);
        }

        DB::beginTransaction();

        try {

            // Handle referral commission if enabled
            $referral = null;
            $referrer = null;
            if (config('settings.referral.enabled')) {
                $referral = $this->handleReferralCommission($user, $validatedData);
                if (!empty($referral)) {
                    $referrer = User::find($referral['to_id']);
                }
            }

            // Create the investment
            $investment = UserInvestment::create([
                'user_id' => $user->id,
                'plan_id' => $validatedData['plan_id'],
                'amount' => $validatedData['investment_amount'],
                'expected_profit' => $actualProjectedReturn,
                'start_date' => Carbon::now(),
                'end_date' => $calculatedEndDate,
                'status' => 'running',
            ]);

            // Record referral commission if available
            if ($referral) {
                Referral::create([
                    'from_id' => $referral['from_id'],
                    'to_id' => $referral['to_id'],
                    'amount' => $referral['amount'],
                    'percent' => $referral['percent'],
                    'user_investment_id' => $investment->id,
                ]);

                $referral['investment'] = $investment;
            }

            $transactionId = 'INVESTMENT_' . Str::uuid();
            $description = "Investment Purchase for: " . $plan->name;

            if ($referral && $referrer) {
                $description .= ". Referral commission of $" . number_format($referral['amount'], 2) .
                    " to " . $referrer->first_name . " " . $referrer->last_name;
            }

            // Record investment transaction
            Transaction::create([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $validatedData['investment_amount'],
                'bank_name' => config('app.name'),
                'account_number' => $user->profile->account_number ?? '',
                'trans_type' => 'debit',
                'receiver_name' => $user->first_name . ' ' . $user->last_name,
                'description' => $description,
                'acct_type' => $user->profile->acct_type ?? 'savings',
                'trans_status' => 'approved'
            ]);

            // Record referral commission transaction if applicable
            if ($referral && $referrer) {
                $referralTransactionId = 'REFERRAL_' . Str::uuid();
                Transaction::create([
                    'user_id' => $referrer->id,
                    'reference_id' => $referralTransactionId,
                    'amount' => $referral['amount'],
                    'bank_name' => config('app.name'),
                    'account_number' => $referrer->profile->account_number ?? '',
                    'trans_type' => 'credit',
                    'receiver_name' => $referrer->first_name . ' ' . $referrer->last_name,
                    'description' => "Referral commission from " . $user->first_name . ' ' . $user->last_name .
                        "'s investment of $" . number_format($validatedData['investment_amount'], 2),
                    'acct_type' => $referrer->profile->acct_type ?? 'savings',
                    'trans_status' => 'approved'
                ]);
            }

            // Update balances
            $user->decrement('balance', $validatedData['investment_amount']);

            // Increment referrer's balance if applicable
            if ($referral && $referrer) {
                $referrer->increment('balance', $referral['amount']);
            }

            DB::commit();

            // Send notifications
            if (config('settings.email_notification')) {
                // Send confirmation to user
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new InvestmentPurchaseConfirmation($investment));

                if ($referral && $referrer) {
                    // Send confirmation to referrer
                    Mail::mailer(config('settings.email_provider'))
                        ->to($referrer->email)
                        ->send(new ReferralCommissionConfirmation($referral));

                    // Also notify admins about referral
                    Notification::send(
                        User::where('role', 'admin')->get(),
                        new NewReferralCommissionNotification($referral)
                    );
                }

                // Send notification to admins about investment
                Notification::send(
                    User::where('role', 'admin')->get(),
                    new NewInvestmentPurchaseNotification($investment)
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Your investment has been created successfully!',
                'redirect' => route('user.investment.show', $investment->id),
                'investment' => $investment
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create investment: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create investment. Please try again.'
            ], 500);
        }
    }

    /**
     * Handle referral commission logic
     */
    private function handleReferralCommission(?Authenticatable $user, array $validatedData)
    {
        // Check if the authenticated user performing the investment was referred
        if (empty($user->ref_by)) {
            return [];
        }

        // Check if the referrer has already earned a referral from this user
        $refExists = Referral::where('from_id', $user->id)
            ->where('to_id', $user->ref_by)
            ->exists();

        if ($refExists) {
            return [];
        }

        // Verify referrer exists
        $referrer = User::find($user->ref_by);
        if (!$referrer) {
            return [];
        }

        // Set the referral variables
        $from_id = $user->id;
        $to_id = $user->ref_by;
        $referralPercentage = config('settings.referral.commission');

        // Validate percentage
        if (!$referralPercentage || $referralPercentage <= 0) {
            return [];
        }

        // Calculate the referral amount based on the percentage
        $percent = $referralPercentage / 100; // Convert percentage to decimal
        $referralAmount = $validatedData['investment_amount'] * $percent;

        return [
            'from_id' => $from_id,
            'to_id' => $to_id,
            'amount' => $referralAmount,
            'percent' => $referralPercentage,
            'investment_amount' => $validatedData['investment_amount'],
            'referrer' => $referrer->only('first_name', 'last_name', 'email'),
            'referred_user' => $user->only('first_name', 'last_name', 'email'),
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(UserInvestment $investment)
    {
        return view('user.investment.show', [
            'title' => 'Account - Investment',
            'investment' => $investment->load('plan', 'plan.category')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function list(Request $request)
    {
        $sort = $request->query('sort');

        $plan_categories = PlanCategory::where('is_active', 1)
            ->orderBy('name')
            ->get();

        $investmentsQuery = UserInvestment::with('plan', 'plan.category')
            ->where('user_id', Auth::id());

        if ($sort === 'completed') {
            $investmentsQuery->where('status', 'completed');
        } elseif ($sort === 'running') {
            $investmentsQuery->where('status', 'running');
        } elseif ($sort === 'liquidated') {
            $investmentsQuery->where('status', 'liquidated');
        } elseif ($sort === 'cancelled') {
            $investmentsQuery->where('status', 'cancelled');
        }

        $investments = $investmentsQuery->latest()->paginate(10);
        $all_investments = UserInvestment::with('plan', 'plan.category')
            ->where('user_id', Auth::id())
            ->get();

        $total_invested = UserInvestment::where('user_id', Auth::id())->sum('amount') / 1000;

        // Calculate additional metrics
        $completed_investments = $all_investments->where('status', 'completed');

        // Calculate profit
        $total_profit = $completed_investments->sum('expected_profit') / 1000;
        $current_value = $total_invested + $total_profit;

        // Category-wise investment data for chart
        $category_data = [];
        foreach ($plan_categories as $category) {
            $category_investments = $all_investments->where('plan.category.id', $category->id);
            $category_data[$category->name] = [
                'running' => $category_investments->where('status', 'running')->sum('amount') / 1000,
                'completed' => $category_investments->where('status', 'completed')->sum('amount') / 1000,
                'liquidated' => $category_investments->where('status', 'liquidated')->sum('amount') / 1000,
                'cancelled' => $category_investments->where('status', 'cancelled')->sum('amount') / 1000,
            ];
        }

        return view('user.investment.list', [
            'title' => 'Account - Investments List',
            'plan_categories' => $plan_categories,
            'investments' => $investments,
            'all_investments' => $all_investments,
            'sort' => $sort,
            'total_invested' => $total_invested,
            'total_profit' => $total_profit,
            'current_value' => $current_value,
            'category_data' => $category_data,
            'cryptoData' => $this->marketPrices->fetchCoinGeckoCoinList()
        ]);
    }

    /**
     * @param UserInvestment $investment
     * @param Request $request
     * @return JsonResponse
     */
    public function liquidate(UserInvestment $investment, Request $request)
    {
        $request->validate([
            'investment_id' => 'required|exists:user_investments,id',
            'liquidation_amount' => 'required|numeric|min:0',
            'is_early_liquidation' => 'required|in:true,false',
            'confirm_liquidation' => 'required_if:is_early_liquidation,true|in:on,off',
            'acknowledge_loss' => 'required_if:is_early_liquidation,true|in:on,off',
        ]);

        $user = Auth::user();

        if ($investment->status != 'running') {
            return response()->json([
                'success' => false,
                'message' => 'This investment has already been marked as completed.',
            ]);
        }

        if ($request->is_early_liquidation) {
            $liquidation_amount = $investment->amount;
        } else {

            // Check if the investment has actually come to maturity
            if (now()->lt($investment->end_date)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Investment has not yet reached maturity. Maturity date is ' . $investment->end_date->format('Y-m-d H:i:s'),
                ]);
            }

            // Investment has matured, proceed with full liquidation
            $liquidation_amount = $investment->amount + $investment->expected_profit;
        }

        try {

            DB::beginTransaction();

            $investment->status = 'liquidated';
            $investment->end_date = now();
            $investment->save();

            $transactionId = 'INVESTMENT_LIQUIDATED_' . Str::uuid();
            $description = $request->is_early_liquidation
                ? "Early Investment Liquidation"
                : "Investment Maturity Liquidation";

            Transaction::create([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $liquidation_amount,
                'bank_name' => config('app.name'),
                'account_number' => $user->profile->account_number,
                'trans_type' => 'credit',
                'receiver_name' => $user->first_name . ' ' . $user->last_name,
                'description' => $description,
                'acct_type' => $user->profile->acct_type ?? 'savings',
                'trans_status' => 'approved'
            ]);

            DB::commit();

            // Send notifications
            if (config('settings.email_notification')) {
                // Send confirmation to user
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new InvestmentLiquidatedConfirmation($investment, $liquidation_amount));

                // Send notification to admins
                Notification::send(
                    User::where('role', 'admin')->get(),
                    new NewInvestmentLiquidatedNotification($investment, $liquidation_amount)
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Investment liquidation request received successfully. We will get back to you soon',
                'investment' => $investment->fresh(),
                'liquidation_amount' => $liquidation_amount,
            ]);

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Liquidation failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while liquidating the investment.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}
