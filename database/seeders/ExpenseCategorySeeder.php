<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Office Supplies',
                'description' => 'Stationery, printing materials, office equipment',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Travel & Transportation',
                'description' => 'Business travel, fuel, vehicle maintenance',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Communication',
                'description' => 'Phone bills, internet, software subscriptions',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Training & Development',
                'description' => 'Employee training courses, certifications',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing & Advertising',
                'description' => 'Digital marketing, ads, promotional materials',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Equipment & Hardware',
                'description' => 'Computers, solar equipment, maintenance tools',
                'color' => '#6B7280',
                'is_active' => true,
            ],
            [
                'name' => 'Utilities',
                'description' => 'Electricity, water, rent, office maintenance',
                'color' => '#059669',
                'is_active' => true,
            ],
            [
                'name' => 'Professional Services',
                'description' => 'Legal fees, consulting, accounting services',
                'color' => '#DC2626',
                'is_active' => true,
            ],
            [
                'name' => 'Miscellaneous',
                'description' => 'Other business expenses not categorized above',
                'color' => '#7C2D12',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\ExpenseCategory::create($category);
        }
    }
}
