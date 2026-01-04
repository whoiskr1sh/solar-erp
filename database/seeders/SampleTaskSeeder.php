<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class SampleTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first project or create one
        $project = Project::first();
        if (!$project) {
            $project = Project::create([
                'name' => 'Sample Solar Installation Project',
                'description' => 'A sample project for testing Gantt chart functionality',
                'project_code' => 'SAMPLE-001',
                'status' => 'active',
                'priority' => 'high',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'budget' => 500000,
                'created_by' => 1,
            ]);
        }

        // Get the first user or create one
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Sample User',
                'email' => 'sample@example.com',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
        }

        // Create sample tasks
        $sampleTasks = [
            [
                'title' => 'Site Survey & Assessment',
                'description' => 'Conduct detailed site survey and assess solar potential',
                'status' => 'completed',
                'priority' => 'high',
                'start_date' => Carbon::now()->subDays(25),
                'due_date' => Carbon::now()->subDays(20),
                'completed_date' => Carbon::now()->subDays(20),
                'estimated_hours' => 16,
                'actual_hours' => 18,
            ],
            [
                'title' => 'Design & Engineering',
                'description' => 'Create detailed solar system design and engineering plans',
                'status' => 'completed',
                'priority' => 'high',
                'start_date' => Carbon::now()->subDays(20),
                'due_date' => Carbon::now()->subDays(10),
                'completed_date' => Carbon::now()->subDays(8),
                'estimated_hours' => 40,
                'actual_hours' => 45,
            ],
            [
                'title' => 'Permit Application',
                'description' => 'Submit and obtain necessary permits from local authorities',
                'status' => 'completed',
                'priority' => 'medium',
                'start_date' => Carbon::now()->subDays(15),
                'due_date' => Carbon::now()->subDays(5),
                'completed_date' => Carbon::now()->subDays(3),
                'estimated_hours' => 8,
                'actual_hours' => 10,
            ],
            [
                'title' => 'Material Procurement',
                'description' => 'Order and receive all required solar panels and equipment',
                'status' => 'in_progress',
                'priority' => 'high',
                'start_date' => Carbon::now()->subDays(10),
                'due_date' => Carbon::now()->addDays(5),
                'estimated_hours' => 12,
                'actual_hours' => 6,
            ],
            [
                'title' => 'Installation Preparation',
                'description' => 'Prepare site and install mounting structures',
                'status' => 'pending',
                'priority' => 'high',
                'start_date' => Carbon::now()->addDays(2),
                'due_date' => Carbon::now()->addDays(10),
                'estimated_hours' => 24,
            ],
            [
                'title' => 'Solar Panel Installation',
                'description' => 'Install solar panels and connect electrical components',
                'status' => 'pending',
                'priority' => 'high',
                'start_date' => Carbon::now()->addDays(8),
                'due_date' => Carbon::now()->addDays(18),
                'estimated_hours' => 32,
            ],
            [
                'title' => 'Electrical Connection',
                'description' => 'Connect to grid and install monitoring systems',
                'status' => 'pending',
                'priority' => 'medium',
                'start_date' => Carbon::now()->addDays(15),
                'due_date' => Carbon::now()->addDays(25),
                'estimated_hours' => 16,
            ],
            [
                'title' => 'Testing & Commissioning',
                'description' => 'Test system performance and commission the installation',
                'status' => 'pending',
                'priority' => 'medium',
                'start_date' => Carbon::now()->addDays(22),
                'due_date' => Carbon::now()->addDays(30),
                'estimated_hours' => 12,
            ],
            [
                'title' => 'Final Inspection',
                'description' => 'Conduct final inspection and handover to customer',
                'status' => 'pending',
                'priority' => 'low',
                'start_date' => Carbon::now()->addDays(28),
                'due_date' => Carbon::now()->addDays(35),
                'estimated_hours' => 8,
            ],
        ];

        foreach ($sampleTasks as $taskData) {
            Task::create([
                ...$taskData,
                'project_id' => $project->id,
                'assigned_to' => $user->id,
                'created_by' => $user->id,
            ]);
        }

        $this->command->info('Sample tasks created successfully!');
    }
}