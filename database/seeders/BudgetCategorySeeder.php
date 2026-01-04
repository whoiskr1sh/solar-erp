<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BudgetCategory;

class BudgetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing budget categories to prevent duplicates
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('budget_categories')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $categories = [
            [
                'name' => 'Equipment',
                'description' => 'Solar equipment, panels, inverters, and installation tools',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing',
                'description' => 'Digital marketing, advertising, and promotional activities',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Staff',
                'description' => 'Employee salaries, training, and benefits',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Transportation',
                'description' => 'Vehicle fleet, fuel, and transportation costs',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Operations',
                'description' => 'Office rent, utilities, and operational expenses',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Technology',
                'description' => 'Software licenses, hardware, and IT infrastructure',
                'color' => '#06B6D4',
                'is_active' => true,
            ],
            [
                'name' => 'Maintenance',
                'description' => 'Equipment maintenance, repairs, and servicing',
                'color' => '#84CC16',
                'is_active' => true,
            ],
            [
                'name' => 'Insurance',
                'description' => 'Company insurance policies and coverage',
                'color' => '#EC4899',
                'is_active' => true,
            ],
            [
                'name' => 'Research & Development',
                'description' => 'R&D projects, innovation, and technology advancement',
                'color' => '#6B7280',
                'is_active' => true,
            ],
            [
                'name' => 'Administrative',
                'description' => 'Legal fees, accounting, and administrative costs',
                'color' => '#1F2937',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            BudgetCategory::create($category);
        }
    }
}
