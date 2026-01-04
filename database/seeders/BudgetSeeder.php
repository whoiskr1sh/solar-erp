<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\BudgetCategory;
use App\Models\Project;
use App\Models\User;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = BudgetCategory::pluck('id')->toArray();
        $projects = Project::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();

        $budgetData = [
            [
                'title' => 'Solar Panel Installation Budget',
                'description' => 'Budget for installing solar panels on residential property',
                'budget_amount' => 15000,
                'actual_amount' => 7500,
                'budget_period' => 'quarterly',
                'start_date' => '2024-01-01',
                'end_date' => '2024-03-31',
                'status' => 'approved',
                'is_approved' => true,
                'notes' => 'Initial installation budget',
            ],
            [
                'title' => 'Equipment Purchase Budget',
                'description' => 'Budget for purchasing solar equipment and tools',
                'budget_amount' => 25000,
                'actual_amount' => 25000,
                'budget_period' => 'yearly',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'status' => 'completed',
                'is_approved' => true,
                'notes' => 'Equipment procurement completed on time',
            ],
            [
                'title' => 'Marketing Campaign Budget',
                'description' => 'Budget for digital marketing and advertising',
                'budget_amount' => 5000,
                'actual_amount' => 3200,
                'budget_period' => 'monthly',
                'start_date' => '2024-02-01',
                'end_date' => '2024-02-29',
                'status' => 'completed',
                'is_approved' => true,
                'notes' => 'Marketing campaign delivered good ROI',
            ],
            [
                'title' => 'Staff Training Budget',
                'description' => 'Budget for employee training and certifications',
                'budget_amount' => 12000,
                'actual_amount' => 8500,
                'budget_period' => 'quarterly',
                'start_date' => '2024-02-01',
                'end_date' => '2024-04-30',
                'status' => 'approved',
                'is_approved' => true,
                'notes' => 'Ongoing training program',
            ],
            [
                'title' => 'Maintenance Budget',
                'description' => 'Budget for equipment maintenance and repairs',
                'budget_amount' => 8000,
                'actual_amount' => 4500,
                'budget_period' => 'yearly',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'status' => 'approved',
                'is_approved' => true,
                'notes' => 'Preventive maintenance program',
            ],
            [
                'title' => 'New Office Setup Budget',
                'description' => 'Budget for setting up new branch office',
                'budget_amount' => 30000,
                'actual_amount' => 35000,
                'budget_period' => 'custom',
                'start_date' => '2024-03-01',
                'end_date' => '2024-05-31',
                'status' => 'pending',
                'is_approved' => false,
                'notes' => 'Over budget due to additional requirements',
            ],
            [
                'title' => 'Research & Development Budget',
                'description' => 'Budget for R&D activities and innovation',
                'budget_amount' => 20000,
                'actual_amount' => 6500,
                'budget_period' => 'yearly',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'status' => 'draft',
                'is_approved' => false,
                'notes' => 'Under review by management',
            ],
            [
                'title' => 'Transportation Fleet Budget',
                'description' => 'Budget for vehicle fleet maintenance and fuel',
                'budget_amount' => 15000,
                'actual_amount' => 12000,
                'budget_period' => 'quarterly',
                'start_date' => '2024-01-01',
                'end_date' => '2024-03-31',
                'status' => 'completed',
                'is_approved' => true,
                'notes' => 'Q1 transportation costs within budget',
            ],
            [
                'title' => 'Software Licensing Budget',
                'description' => 'Budget for software licenses and subscriptions',
                'budget_amount' => 10000,
                'actual_amount' => 10000,
                'budget_period' => 'yearly',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'status' => 'approved',
                'is_approved' => true,
                'notes' => 'All licenses renewed successfully',
            ],
            [
                'title' => 'Insurance Policy Budget',
                'description' => 'Budget for company insurance policies',
                'budget_amount' => 18000,
                'actual_amount' => 18500,
                'budget_period' => 'yearly',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'status' => 'approved',
                'is_approved' => true,
                'notes' => 'Insurance premium slightly over budget',
            ],
        ];

        foreach ($budgetData as $data) {
            Budget::create(array_merge($data, [
                'budget_category_id' => fake()->randomElement($categories),
                'project_id' => count($projects) > 0 ? fake()->randomElement($projects) : null,
                'created_by' => fake()->randomElement($users),
                'approved_by' => $data['is_approved'] ? fake()->randomElement($users) : null,
                'approved_at' => $data['is_approved'] ? fake()->dateTimeBetween($data['start_date'], now()) : null,
                'currency' => fake()->randomElement(['INR', 'USD', 'EUR']),
            ]));
        }
    }
}
