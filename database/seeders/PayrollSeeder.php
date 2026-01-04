<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payroll;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payrolls = [
            [
                'employee_id' => 'EMP001',
                'payroll_month' => 'September',
                'payroll_year' => '2025',
                'basic_salary' => 45000.00,
                'allowances' => 5000.00,
                'deductions' => 2000.00,
                'net_salary' => 48000.00,
                'status' => 'paid',
                'payment_date' => now()->subDays(5),
            ],
            [
                'employee_id' => 'EMP002',
                'payroll_month' => 'September',
                'payroll_year' => '2025',
                'basic_salary' => 55000.00,
                'allowances' => 6000.00,
                'deductions' => 2500.00,
                'net_salary' => 58500.00,
                'status' => 'paid',
                'payment_date' => now()->subDays(5),
            ],
            [
                'employee_id' => 'EMP003',
                'payroll_month' => 'September',
                'payroll_year' => '2025',
                'basic_salary' => 60000.00,
                'allowances' => 7000.00,
                'deductions' => 3000.00,
                'net_salary' => 64000.00,
                'status' => 'paid',
                'payment_date' => now()->subDays(5),
            ],
            [
                'employee_id' => 'EMP001',
                'payroll_month' => 'October',
                'payroll_year' => '2025',
                'basic_salary' => 45000.00,
                'allowances' => 5000.00,
                'deductions' => 2000.00,
                'net_salary' => 48000.00,
                'status' => 'pending',
            ],
            [
                'employee_id' => 'EMP002',
                'payroll_month' => 'October',
                'payroll_year' => '2025',
                'basic_salary' => 55000.00,
                'allowances' => 6000.00,
                'deductions' => 2500.00,
                'net_salary' => 58500.00,
                'status' => 'pending',
            ],
            [
                'employee_id' => 'EMP003',
                'payroll_month' => 'October',
                'payroll_year' => '2025',
                'basic_salary' => 60000.00,
                'allowances' => 7000.00,
                'deductions' => 3000.00,
                'net_salary' => 64000.00,
                'status' => 'pending',
            ],
        ];

        foreach ($payrolls as $payroll) {
            Payroll::create($payroll);
        }
    }
}