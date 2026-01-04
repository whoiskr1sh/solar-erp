<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobApplication;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobApplications = [
            [
                'application_number' => 'APP-2025-0001',
                'job_title' => 'Solar Engineer',
                'applicant_name' => 'Amit Kumar',
                'applicant_email' => 'amit@email.com',
                'applicant_phone' => '+91-9876543210',
                'resume_path' => '/resumes/amit_kumar.pdf',
                'cover_letter' => 'I am interested in the Solar Engineer position and have 3 years of experience in renewable energy.',
                'status' => 'interview',
                'application_date' => now()->subDays(5),
                'interview_date' => now()->addDays(2),
                'interview_notes' => 'Strong technical background, good communication skills',
                'interviewer_name' => 'HR Manager',
            ],
            [
                'application_number' => 'APP-2025-0002',
                'job_title' => 'Project Manager',
                'applicant_name' => 'Priya Sharma',
                'applicant_email' => 'priya@email.com',
                'applicant_phone' => '+91-9876543211',
                'resume_path' => '/resumes/priya_sharma.pdf',
                'cover_letter' => 'Experienced project manager with 5 years in construction industry.',
                'status' => 'selected',
                'application_date' => now()->subDays(10),
                'interview_date' => now()->subDays(3),
                'interview_notes' => 'Excellent leadership skills, relevant experience',
                'interviewer_name' => 'Project Director',
            ],
            [
                'application_number' => 'APP-2025-0003',
                'job_title' => 'Sales Executive',
                'applicant_name' => 'Rajesh Singh',
                'applicant_email' => 'rajesh@email.com',
                'applicant_phone' => '+91-9876543212',
                'resume_path' => '/resumes/rajesh_singh.pdf',
                'cover_letter' => 'Passionate about solar energy sales with proven track record.',
                'status' => 'applied',
                'application_date' => now()->subDays(2),
            ],
        ];

        foreach ($jobApplications as $application) {
            JobApplication::create($application);
        }
    }
}