<?php

namespace Database\Seeders;

use App\Models\PlanCategory;
use Illuminate\Database\Seeder;

class PlanCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Company Shares',
                'slug' => 'company-shares',
                'description' => 'Invest directly in company stocks and equity',
                'icon' => 'bi bi-graph-up',
                'color' => '#4CAF50',
                'risk_level' => 'high',
                'liquidity' => 'daily',
            ],
            [
                'name' => 'Mutual Funds Buy/SIP',
                'slug' => 'mutual-funds',
                'description' => 'Systematic investment plans and mutual fund purchases',
                'icon' => 'bi bi-pie-chart',
                'color' => '#2196F3',
                'risk_level' => 'medium',
                'liquidity' => 'weekly',
            ],
            [
                'name' => 'Fixed Deposit Schemes',
                'slug' => 'fixed-deposits',
                'description' => 'Fixed return investment with guaranteed principal',
                'icon' => 'bi bi-safe',
                'color' => '#FF9800',
                'risk_level' => 'low',
                'liquidity' => 'term',
            ],
            [
                'name' => 'Investment Plans',
                'slug' => 'investment-plans',
                'description' => 'Diversified investment options for wealth creation',
                'icon' => 'bi bi-wallet2',
                'color' => '#9C27B0',
                'risk_level' => 'medium',
                'liquidity' => 'monthly',
            ],
            [
                'name' => 'Retirement Plans',
                'slug' => 'retirement-plans',
                'description' => 'Long-term savings for retirement with tax benefits',
                'icon' => 'bi bi-umbrella',
                'color' => '#607D8B',
                'risk_level' => 'low',
                'liquidity' => 'term',
            ],
            [
                'name' => 'Tax Saving Investments',
                'slug' => 'tax-saving',
                'description' => 'Investments that offer tax deductions under relevant sections',
                'icon' => 'bi bi-file-earmark-text',
                'color' => '#E91E63',
                'risk_level' => 'medium',
                'liquidity' => 'term',
            ],
            [
                'name' => 'Guaranteed Return',
                'slug' => 'guaranteed-return',
                'description' => 'Investments with assured returns and capital protection',
                'icon' => 'bi bi-shield-check',
                'color' => '#00BCD4',
                'risk_level' => 'low',
                'liquidity' => 'term',
            ],
            [
                'name' => 'Government Securities',
                'slug' => 'government-securities',
                'description' => 'Sovereign guaranteed bonds and treasury instruments',
                'icon' => 'bi bi-building',
                'color' => '#795548',
                'risk_level' => 'low',
                'liquidity' => 'monthly',
            ],
        ];

        foreach ($categories as $category) {
            PlanCategory::create($category);
        }
    }
}
