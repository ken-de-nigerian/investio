<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanCategory;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            // Company Shares
            [
                'name' => 'Blue Chip Stocks',
                'slug' => 'blue-chip-stocks',
                'description' => 'Investment in top-tier company shares with stable returns',
                'min_amount' => 5000,
                'interest_rate' => 12.5,
                'duration_days' => 365,
                'plan_category_id' => PlanCategory::where('slug', 'company-shares')->first()->id,
            ],
            [
                'name' => 'Growth Stocks',
                'slug' => 'growth-stocks',
                'description' => 'High-growth potential company shares',
                'min_amount' => 10000,
                'interest_rate' => 18.2,
                'duration_days' => 730,
                'plan_category_id' => PlanCategory::where('slug', 'company-shares')->first()->id,
            ],

            // Mutual Funds
            [
                'name' => 'Equity SIP',
                'slug' => 'equity-sip',
                'description' => 'Systematic investment in equity mutual funds',
                'min_amount' => 1000,
                'interest_rate' => 10.8,
                'duration_days' => 180,
                'plan_category_id' => PlanCategory::where('slug', 'mutual-funds')->first()->id,
            ],
            [
                'name' => 'Debt Fund',
                'slug' => 'debt-fund',
                'description' => 'Low-risk debt mutual fund investment',
                'min_amount' => 5000,
                'interest_rate' => 7.5,
                'duration_days' => 365,
                'plan_category_id' => PlanCategory::where('slug', 'mutual-funds')->first()->id,
            ],

            // Fixed Deposits
            [
                'name' => '1-Year FD',
                'slug' => '1-year-fd',
                'description' => 'Fixed deposit with 1 year lock-in period',
                'min_amount' => 10000,
                'interest_rate' => 6.5,
                'duration_days' => 365,
                'plan_category_id' => PlanCategory::where('slug', 'fixed-deposits')->first()->id,
            ],
            [
                'name' => 'Senior Citizen FD',
                'slug' => 'senior-fd',
                'description' => 'Higher interest fixed deposit for senior citizens',
                'min_amount' => 5000,
                'interest_rate' => 7.25,
                'duration_days' => 365,
                'plan_category_id' => PlanCategory::where('slug', 'fixed-deposits')->first()->id,
            ],

            // Investment Plans
            [
                'name' => 'Wealth Builder',
                'slug' => 'wealth-builder',
                'description' => 'Diversified portfolio for long-term wealth creation',
                'min_amount' => 20000,
                'interest_rate' => 11.5,
                'duration_days' => 1095,
                'plan_category_id' => PlanCategory::where('slug', 'investment-plans')->first()->id,
            ],

            // Retirement Plans
            [
                'name' => 'Pension Plan',
                'slug' => 'pension-plan',
                'description' => 'Long-term retirement savings with annuity options',
                'min_amount' => 20000,
                'interest_rate' => 8.5,
                'duration_days' => 3650,
                'plan_category_id' => PlanCategory::where('slug', 'retirement-plans')->first()->id,
            ],

            // Tax Saving
            [
                'name' => 'ELSS',
                'slug' => 'elss',
                'description' => 'Equity Linked Savings Scheme with tax benefits',
                'min_amount' => 500,
                'interest_rate' => 12.0,
                'duration_days' => 1095,
                'plan_category_id' => PlanCategory::where('slug', 'tax-saving')->first()->id,
            ],

            // Guaranteed Return
            [
                'name' => 'Capital Protection',
                'slug' => 'capital-protection',
                'description' => 'Guaranteed principal with moderate returns',
                'min_amount' => 15000,
                'interest_rate' => 5.5,
                'duration_days' => 730,
                'plan_category_id' => PlanCategory::where('slug', 'guaranteed-return')->first()->id,
            ],

            // Government Securities
            [
                'name' => 'Treasury Bonds',
                'slug' => 'treasury-bonds',
                'description' => 'Government issued treasury bonds',
                'min_amount' => 10000,
                'interest_rate' => 6.0,
                'duration_days' => 1825,
                'plan_category_id' => PlanCategory::where('slug', 'government-securities')->first()->id,
            ]
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }
    }
}
