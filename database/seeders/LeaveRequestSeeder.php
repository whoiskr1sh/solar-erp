<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaveRequest;

class LeaveRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveRequests = [
            [
                'employee_id' => 'EMP001',
                'leave_type' => 'Sick Leave',
                'start_date' => now()->addDays(2),
                'end_date' => now()->addDays(3),
                'total_days' => 2,
                'reason' => 'Medical appointment',
                'status' => 'pending',
            ],
            [
                'employee_id' => 'EMP002',
                'leave_type' => 'Personal Leave',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(7),
                'total_days' => 3,
                'reason' => 'Family function',
                'status' => 'approved',
                'approved_by' => 'HR Manager',
                'approved_date' => now()->subDays(1),
            ],
            [
                'employee_id' => 'EMP003',
                'leave_type' => 'Annual Leave',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(15),
                'total_days' => 6,
                'reason' => 'Vacation',
                'status' => 'rejected',
                'approved_by' => 'HR Manager',
                'approved_date' => now()->subDays(2),
                'comments' => 'Peak season, cannot approve leave',
            ],
        ];

        foreach ($leaveRequests as $leaveRequest) {
            LeaveRequest::create($leaveRequest);
        }
    }
}