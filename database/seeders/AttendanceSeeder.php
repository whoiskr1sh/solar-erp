<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendances = [
            [
                'employee_id' => 'EMP001',
                'date' => now()->subDays(1),
                'check_in' => '09:00:00',
                'check_out' => '18:00:00',
                'total_hours' => 9,
                'status' => 'present',
            ],
            [
                'employee_id' => 'EMP002',
                'date' => now()->subDays(1),
                'check_in' => '09:15:00',
                'check_out' => '18:30:00',
                'total_hours' => 9,
                'status' => 'late',
                'remarks' => 'Traffic jam',
            ],
            [
                'employee_id' => 'EMP003',
                'date' => now()->subDays(1),
                'check_in' => null,
                'check_out' => null,
                'total_hours' => 0,
                'status' => 'absent',
                'remarks' => 'Sick leave',
            ],
            [
                'employee_id' => 'EMP001',
                'date' => now(),
                'check_in' => '09:00:00',
                'check_out' => null,
                'total_hours' => null,
                'status' => 'present',
            ],
            [
                'employee_id' => 'EMP002',
                'date' => now(),
                'check_in' => '09:00:00',
                'check_out' => null,
                'total_hours' => null,
                'status' => 'present',
            ],
            [
                'employee_id' => 'EMP003',
                'date' => now(),
                'check_in' => '09:00:00',
                'check_out' => null,
                'total_hours' => null,
                'status' => 'present',
            ],
        ];

        foreach ($attendances as $attendance) {
            Attendance::create($attendance);
        }
    }
}