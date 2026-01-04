<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExpenseClaim;

class ExpenseClaimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseClaims = [
            [
                'employee_id' => 'EMP001',
                'claim_number' => 'EXP-2025-0001',
                'expense_type' => 'Travel',
                'expense_date' => now()->subDays(5),
                'amount' => 2500.00,
                'description' => 'Client meeting travel expenses',
                'receipt_path' => '/receipts/travel_001.pdf',
                'status' => 'approved',
                'approved_by' => 'Manager',
                'approved_date' => now()->subDays(2),
            ],
            [
                'employee_id' => 'EMP002',
                'claim_number' => 'EXP-2025-0002',
                'expense_type' => 'Meals',
                'expense_date' => now()->subDays(3),
                'amount' => 1200.00,
                'description' => 'Team lunch meeting',
                'receipt_path' => '/receipts/meals_001.pdf',
                'status' => 'submitted',
            ],
            [
                'employee_id' => 'EMP003',
                'claim_number' => 'EXP-2025-0003',
                'expense_type' => 'Office Supplies',
                'expense_date' => now()->subDays(7),
                'amount' => 800.00,
                'description' => 'Stationery and office materials',
                'receipt_path' => '/receipts/supplies_001.pdf',
                'status' => 'paid',
                'approved_by' => 'Admin Manager',
                'approved_date' => now()->subDays(4),
            ],
        ];

        foreach ($expenseClaims as $claim) {
            ExpenseClaim::create($claim);
        }
    }
}