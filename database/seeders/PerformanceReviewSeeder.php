<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PerformanceReview;

class PerformanceReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $performanceReviews = [
            [
                'employee_id' => 'EMP001',
                'review_period' => 'Q3 2025',
                'review_date' => now()->subDays(10),
                'overall_rating' => 4,
                'goals_achieved' => 'Completed all assigned projects on time, improved team efficiency by 15%',
                'areas_for_improvement' => 'Need to improve communication skills and leadership qualities',
                'manager_comments' => 'Excellent technical skills, good team player',
                'employee_comments' => 'Looking forward to taking on more responsibilities',
                'status' => 'completed',
                'reviewed_by' => 'HR Manager',
            ],
            [
                'employee_id' => 'EMP002',
                'review_period' => 'Q3 2025',
                'review_date' => now()->subDays(5),
                'overall_rating' => 5,
                'goals_achieved' => 'Successfully managed 3 major projects, mentored junior developers',
                'areas_for_improvement' => 'Continue current performance level',
                'manager_comments' => 'Outstanding performance, ready for promotion',
                'employee_comments' => 'Thank you for the recognition',
                'status' => 'completed',
                'reviewed_by' => 'Project Manager',
            ],
            [
                'employee_id' => 'EMP003',
                'review_period' => 'Q3 2025',
                'review_date' => now()->addDays(5),
                'overall_rating' => 0,
                'goals_achieved' => '',
                'areas_for_improvement' => '',
                'manager_comments' => '',
                'employee_comments' => '',
                'status' => 'submitted',
                'reviewed_by' => 'Sales Manager',
            ],
        ];

        foreach ($performanceReviews as $review) {
            PerformanceReview::create($review);
        }
    }
}