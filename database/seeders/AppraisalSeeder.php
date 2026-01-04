<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appraisal;

class AppraisalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appraisals = [
            [
                'employee_id' => 'EMP001',
                'appraisal_type' => 'Annual',
                'appraisal_date' => now()->subDays(15),
                'next_review_date' => now()->addYear(),
                'performance_score' => 4,
                'strengths' => 'Technical expertise, problem-solving skills, teamwork',
                'weaknesses' => 'Public speaking, time management',
                'development_plan' => 'Attend communication workshops, time management training',
                'manager_feedback' => 'Consistent performer, reliable team member',
                'status' => 'completed',
                'appraiser_name' => 'HR Manager',
            ],
            [
                'employee_id' => 'EMP002',
                'appraisal_type' => 'Mid-Year',
                'appraisal_date' => now()->subDays(8),
                'next_review_date' => now()->addMonths(6),
                'performance_score' => 5,
                'strengths' => 'Leadership, project management, innovation',
                'weaknesses' => 'None identified',
                'development_plan' => 'Continue current trajectory, consider leadership role',
                'manager_feedback' => 'Exceptional performance, role model for others',
                'status' => 'completed',
                'appraiser_name' => 'Project Manager',
            ],
            [
                'employee_id' => 'EMP003',
                'appraisal_type' => 'Annual',
                'appraisal_date' => now()->addDays(10),
                'next_review_date' => now()->addYear(),
                'performance_score' => 0,
                'strengths' => '',
                'weaknesses' => '',
                'development_plan' => '',
                'manager_feedback' => '',
                'status' => 'scheduled',
                'appraiser_name' => 'Sales Manager',
            ],
        ];

        foreach ($appraisals as $appraisal) {
            Appraisal::create($appraisal);
        }
    }
}