<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalarySlip;

class SalarySlipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salarySlips = [
            [
                'employee_id' => 'EMP001',
                'slip_number' => 'SLIP-2025-0001',
                'payroll_month' => 'September',
                'payroll_year' => '2025',
                'basic_salary' => 45000.00,
                'hra' => 13500.00,
                'da' => 9000.00,
                'allowances' => 5000.00,
                'pf' => 5400.00,
                'esi' => 675.00,
                'tax' => 2000.00,
                'other_deductions' => 500.00,
                'net_salary' => 54925.00,
                'generated_date' => now()->subDays(5),
                'status' => 'sent',
            ],
            [
                'employee_id' => 'EMP002',
                'slip_number' => 'SLIP-2025-0002',
                'payroll_month' => 'September',
                'payroll_year' => '2025',
                'basic_salary' => 55000.00,
                'hra' => 16500.00,
                'da' => 11000.00,
                'allowances' => 6000.00,
                'pf' => 6600.00,
                'esi' => 825.00,
                'tax' => 3000.00,
                'other_deductions' => 600.00,
                'net_salary' => 70475.00,
                'generated_date' => now()->subDays(5),
                'status' => 'sent',
            ],
            [
                'employee_id' => 'EMP003',
                'slip_number' => 'SLIP-2025-0003',
                'payroll_month' => 'September',
                'payroll_year' => '2025',
                'basic_salary' => 60000.00,
                'hra' => 18000.00,
                'da' => 12000.00,
                'allowances' => 7000.00,
                'pf' => 7200.00,
                'esi' => 900.00,
                'tax' => 4000.00,
                'other_deductions' => 700.00,
                'net_salary' => 75200.00,
                'generated_date' => now()->subDays(5),
                'status' => 'downloaded',
            ],
        ];

        foreach ($salarySlips as $slip) {
            SalarySlip::create($slip);
        }
    }
}