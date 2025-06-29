<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PlanCategory;
use App\Models\Transaction;
use App\Models\UserInvestment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $incomeStats = $this->getMonthlyChange('credit');
        $expenseStats = $this->getMonthlyChange('debit');

        $plan_categories = PlanCategory::where('is_active', 1)
            ->orderBy('name')
            ->get();

        $total_invested = UserInvestment::where('user_id', Auth::id())->sum('amount') / 1000;

        // Category-wise investment data for chart
        $category_data = [];
        foreach ($plan_categories as $category) {
            $category_investments = UserInvestment::with('plan', 'plan.category')
                ->where('user_id', Auth::id())
                ->whereHas('plan.category', fn($query) => $query->where('id', $category->id))
                ->get();
            $category_data[$category->name] = [
                'running' => $category_investments->where('status', 'running')->sum('amount') / 1000,
                'completed' => $category_investments->where('status', 'completed')->sum('amount') / 1000,
                'liquidated' => $category_investments->where('status', 'liquidated')->sum('amount') / 1000,
                'cancelled' => $category_investments->where('status', 'cancelled')->sum('amount') / 1000,
            ];
        }

        return view('user.statistics.index', [
            'title' => 'Account - Statistics',
            'income' => $incomeStats['amount'],
            'incomeChange' => $incomeStats['change'],
            'incomeDirection' => $incomeStats['direction'],
            'expense' => $expenseStats['amount'],
            'expenseChange' => $expenseStats['change'],
            'expenseDirection' => $expenseStats['direction'],
            'plan_categories' => $plan_categories,
            'total_invested' => $total_invested,
            'category_data' => $category_data,
            'transactions' => Transaction::where('user_id', Auth::id())->latest()->limit(10)->get()
        ]);
    }

    /**
     * Calculate monthly change for a given transaction type.
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
     * Get daily income and expense data for the last 30 days.
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
