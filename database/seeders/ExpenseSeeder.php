<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\ExpenseCategory::all();
        $users = \App\Models\User::all();
        
        if ($categories->isEmpty() || $users->isEmpty()) {
            return;
        }

        $sampleExpenses = [
            [
                'title' => 'Office Supplies Purchase',
                'description' => 'Monthly office supplies including paper, pens, and notebooks',
                'amount' => 125.50,
                'currency' => 'USD',
                'payment_method' => 'card',
                'expense_date' => now()->subDays(5),
                'status' => 'approved',
                'notes' => 'Used for Q4 planning meeting materials',
            ],
            [
                'title' => 'Client Meeting Lunch',
                'description' => 'Business lunch with potential solar installation client',
                'amount' => 78.30,
                'currency' => 'USD',
                'payment_method' => 'card',
                'expense_date' => now()->subDays(3),
                'status' => 'paid',
                'notes' => 'Great discussion about rooftop solar project',
            ],
            [
                'title' => 'Software Subscription',
                'description' => 'Annual subscription for project management software',
                'amount' => 240.00,
                'currency' => 'USD',
                'payment_method' => 'transfer',
                'expense_date' => now()->subDays(7),
                'status' => 'approved',
                'notes' => 'Essential tool for solar project tracking',
            ],
            [
                'title' => 'Travel to Site Visit',
                'description' => 'Gas and toll costs for residential solar site assessment',
                'amount' => 42.50,
                'currency' => 'USD',
                'payment_method' => 'cash',
                'expense_date' => now()->subDays(2),
                'status' => 'pending',
                'notes' => 'Site visit for 10kW residential installation',
            ],
            [
                'title' => 'Equipment Maintenance',
                'description' => 'Professional maintenance for solar panel testing equipment',
                'amount' => 325.75,
                'currency' => 'USD',
                'payment_method' => 'cheque',
                'expense_date' => now()->subDays(10),
                'status' => 'approved',
                'notes' => 'Annual calibration and maintenance',
            ],
            [
                'title' => 'Training Course',
                'description' => 'Solar installation safety certification course',
                'amount' => 450.00,
                'currency' => 'USD',
                'payment_method' => 'card',
                'expense_date' => now()->subDays(15),
                'status' => 'paid',
                'notes' => 'Required certification for new team member',
            ],
            [
                'title' => 'Marketing Materials',
                'description' => 'Brochures and business cards for solar installation services',
                'amount' => 89.90,
                'currency' => 'USD',
                'payment_method' => 'card',
                'expense_date' => now()->subDays(4),
                'status' => 'approved',
                'notes' => 'Updated with new pricing and packages',
            ],
            [
                'title' => 'Internet Service',
                'description' => 'Monthly office internet service',
                'amount' => 65.00,
                'currency' => 'USD',
                'payment_method' => 'transfer',
                'expense_date' => now()->subDays(8),
                'status' => 'paid',
                'notes' => 'Business broadband connection',
            ],
            [
                'title' => 'Legal Consultation',
                'description' => 'Legal advice on solar installation contracts',
                'amount' => 200.00,
                'currency' => 'USD',
                'payment_method' => 'cheque',
                'expense_date' => now()->subDays(12),
                'status' => 'approved',
                'notes' => 'Contract template revision',
            ],
            [
                'title' => 'Equipment Rental',
                'description' => 'Lift rental for solar panel installation on 2-story building',
                'amount' => 185.00,
                'currency' => 'USD',
                'payment_method' => 'card',
                'expense_date' => now()->subDays(6),
                'status' => 'rejected',
                'notes' => 'Should have been planned in project budget',
            ],
        ];

        foreach ($sampleExpenses as $expenseData) {
            \App\Models\Expense::create(array_merge($expenseData, [
                'expense_category_id' => $categories->random()->id,
                'created_by' => $users->random()->id,
                'approved_by' => $expenseData['status'] === 'approved' ? $users->random()->id : null,
                'approved_at' => $expenseData['status'] === 'approved' ? now()->subDays(rand(1, 5)) : null,
            ]));
        }
    }
}
