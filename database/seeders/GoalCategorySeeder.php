<?php

namespace Database\Seeders;

use App\Models\GoalCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GoalCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Emergency Fund',
                'description' => 'Build a safety net for unexpected expenses and financial emergencies',
                'icon' => 'shield-check',
                'color' => '#dc2626', // red-600
            ],
            [
                'name' => 'Vacation & Travel',
                'description' => 'Save for your dream vacation, trips, and travel adventures',
                'icon' => 'airplane',
                'color' => '#2563eb', // blue-600
            ],
            [
                'name' => 'Home & Real Estate',
                'description' => 'Save for home down payment, mortgage, or property investment',
                'icon' => 'house',
                'color' => '#059669', // emerald-600
            ],
            [
                'name' => 'Car & Transportation',
                'description' => 'Save for a new car, vehicle maintenance, or transportation needs',
                'icon' => 'car-front',
                'color' => '#7c3aed', // violet-600
            ],
            [
                'name' => 'Education',
                'description' => 'Invest in education, courses, certifications, or student loans',
                'icon' => 'mortarboard',
                'color' => '#ea580c', // orange-600
            ],
            [
                'name' => 'Wedding',
                'description' => 'Plan and save for your perfect wedding day',
                'icon' => 'heart',
                'color' => '#ec4899', // pink-500
            ],
            [
                'name' => 'Baby & Family',
                'description' => 'Prepare financially for a new baby or family expenses',
                'icon' => 'people',
                'color' => '#10b981', // emerald-500
            ],
            [
                'name' => 'Retirement',
                'description' => 'Build your retirement fund for a secure financial future',
                'icon' => 'clock-history',
                'color' => '#6b7280', // gray-500
            ],
            [
                'name' => 'Health & Medical',
                'description' => 'Save for medical expenses, health insurance, or wellness goals',
                'icon' => 'heart-pulse',
                'color' => '#dc2626', // red-600
            ],
            [
                'name' => 'Technology & Gadgets',
                'description' => 'Save for the latest tech, gadgets, and electronic devices',
                'icon' => 'phone',
                'color' => '#4f46e5', // indigo-600
            ],
            [
                'name' => 'Business & Investment',
                'description' => 'Fund your business venture or investment opportunities',
                'icon' => 'briefcase',
                'color' => '#059669', // emerald-600
            ],
            [
                'name' => 'Hobbies & Entertainment',
                'description' => 'Save for hobbies, sports equipment, entertainment, and fun activities',
                'icon' => 'puzzle',
                'color' => '#f59e0b', // amber-500
            ],
            [
                'name' => 'Gifts & Special Occasions',
                'description' => 'Save for birthdays, holidays, anniversaries, and special gifts',
                'icon' => 'gift',
                'color' => '#ec4899', // pink-500
            ],
            [
                'name' => 'Debt Payoff',
                'description' => 'Create a plan to pay off credit cards, loans, and other debts',
                'icon' => 'credit-card',
                'color' => '#dc2626', // red-600
            ],
            [
                'name' => 'Home Improvement',
                'description' => 'Renovate, decorate, or improve your living space',
                'icon' => 'tools',
                'color' => '#d97706', // amber-600
            ],
            [
                'name' => 'Fitness & Sports',
                'description' => 'Invest in gym memberships, sports equipment, and fitness goals',
                'icon' => 'activity',
                'color' => '#dc2626', // red-600
            ],
            [
                'name' => 'Pet Care',
                'description' => 'Save for pet expenses, veterinary care, and pet supplies',
                'icon' => 'heart',
                'color' => '#059669', // emerald-600
            ],
            [
                'name' => 'Fashion & Beauty',
                'description' => 'Save for clothing, accessories, beauty treatments, and style upgrades',
                'icon' => 'stars',
                'color' => '#ec4899', // pink-500
            ],
            [
                'name' => 'Books & Learning',
                'description' => 'Invest in books, online courses, and personal development',
                'icon' => 'book',
                'color' => '#7c3aed', // violet-600
            ],
            [
                'name' => 'Other',
                'description' => 'Custom savings goals that don\'t fit other categories',
                'icon' => 'three-dots',
                'color' => '#6b7280', // gray-500
            ],
        ];

        foreach ($categories as $category) {
            GoalCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'color' => $category['color'],
                'is_active' => true,
            ]);
        }
    }
}
