<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'employee_id' => 'EMP001',
                'first_name' => 'Rajesh',
                'last_name' => 'Kumar',
                'email' => 'rajesh@company.com',
                'phone' => '+91-9876543210',
                'date_of_birth' => '1985-05-15',
                'address' => 'Mumbai, Maharashtra',
                'department' => 'Operations',
                'designation' => 'Senior Technician',
                'joining_date' => now()->subYear(),
                'salary' => 45000.00,
                'employment_type' => 'full_time',
                'status' => 'active',
                'emergency_contact' => 'Mrs. Kumar',
                'emergency_phone' => '+91-9876543211',
                'skills' => 'Solar Installation, Maintenance, Troubleshooting',
            ],
            [
                'employee_id' => 'EMP002',
                'first_name' => 'Priya',
                'last_name' => 'Sharma',
                'email' => 'priya@company.com',
                'phone' => '+91-9876543212',
                'date_of_birth' => '1990-08-22',
                'address' => 'Delhi, NCR',
                'department' => 'Engineering',
                'designation' => 'Project Engineer',
                'joining_date' => now()->subMonths(8),
                'salary' => 55000.00,
                'employment_type' => 'full_time',
                'status' => 'active',
                'emergency_contact' => 'Mr. Sharma',
                'emergency_phone' => '+91-9876543213',
                'skills' => 'Project Management, Solar Design, AutoCAD',
            ],
            [
                'employee_id' => 'EMP003',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@company.com',
                'phone' => '+91-9876543214',
                'date_of_birth' => '1988-12-10',
                'address' => 'Bangalore, Karnataka',
                'department' => 'Sales',
                'designation' => 'Sales Manager',
                'joining_date' => now()->subMonths(6),
                'salary' => 60000.00,
                'employment_type' => 'full_time',
                'status' => 'active',
                'emergency_contact' => 'Mrs. Doe',
                'emergency_phone' => '+91-9876543215',
                'skills' => 'Sales, Customer Relations, Solar Products',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}