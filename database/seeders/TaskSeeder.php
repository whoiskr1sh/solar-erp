<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();

        if ($projects->isEmpty() || $users->isEmpty()) {
            return;
        }

        $tasks = [
            [
                'title' => 'Site Survey and Assessment',
                'description' => 'Conduct detailed site survey for solar panel installation including roof condition, shading analysis, and electrical infrastructure assessment.',
                'status' => 'completed',
                'priority' => 'high',
                'start_date' => now()->subDays(10),
                'due_date' => now()->subDays(5),
                'completed_date' => now()->subDays(4),
                'project_id' => $projects->random()->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->first()->id,
                'estimated_hours' => 8,
                'actual_hours' => 6,
            ],
            [
                'title' => 'Design Solar Panel Layout',
                'description' => 'Create detailed solar panel layout design including panel placement, wiring diagram, and inverter positioning.',
                'status' => 'in_progress',
                'priority' => 'high',
                'start_date' => now()->subDays(3),
                'due_date' => now()->addDays(2),
                'project_id' => $projects->random()->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->first()->id,
                'estimated_hours' => 12,
                'actual_hours' => 4,
            ],
            [
                'title' => 'Procure Solar Panels',
                'description' => 'Source and procure high-quality solar panels from approved vendors including quality checks and delivery coordination.',
                'status' => 'pending',
                'priority' => 'urgent',
                'start_date' => now()->addDays(1),
                'due_date' => now()->addDays(7),
                'project_id' => $projects->random()->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->first()->id,
                'estimated_hours' => 16,
                'actual_hours' => 0,
            ],
            [
                'title' => 'Install Mounting Structure',
                'description' => 'Install roof mounting structure for solar panels ensuring proper waterproofing and structural integrity.',
                'status' => 'pending',
                'priority' => 'medium',
                'start_date' => now()->addDays(5),
                'due_date' => now()->addDays(10),
                'project_id' => $projects->random()->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->first()->id,
                'estimated_hours' => 20,
                'actual_hours' => 0,
            ],
            [
                'title' => 'Install Solar Panels',
                'description' => 'Install solar panels on the mounting structure with proper alignment and secure fastening.',
                'status' => 'pending',
                'priority' => 'high',
                'start_date' => now()->addDays(8),
                'due_date' => now()->addDays(12),
                'project_id' => $projects->random()->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->first()->id,
                'estimated_hours' => 24,
                'actual_hours' => 0,
            ],
            [
                'title' => 'Electrical Wiring and Connections',
                'description' => 'Complete electrical wiring from panels to inverter and grid connection point with proper safety measures.',
                'status' => 'pending',
                'priority' => 'high',
                'start_date' => now()->addDays(10),
                'due_date' => now()->addDays(14),
                'project_id' => $projects->random()->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->first()->id,
                'estimated_hours' => 16,
                'actual_hours' => 0,
            ],
            [
                'title' => 'Install Inverter and Monitoring System',
                'description' => 'Install solar inverter and monitoring system for performance tracking and remote monitoring.',
                'status' => 'pending',
                'priority' => 'medium',
                'start_date' => now()->addDays(12),
                'due_date' => now()->addDays(16),
                'project_id' => $projects->random()->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->first()->id,
                'estimated_hours' => 8,
                'actual_hours' => 0,
            ],
            [
                'title' => 'System Testing and Commissioning',
                'description' => 'Conduct comprehensive system testing including performance verification, safety checks, and commissioning.',
                'status' => 'pending',
                'priority' => 'urgent',
                'start_date' => now()->addDays(15),
                'due_date' => now()->addDays(18),
                'project_id' => $projects->random()->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->first()->id,
                'estimated_hours' => 12,
                'actual_hours' => 0,
            ],
            [
                'title' => 'Customer Training and Handover',
                'description' => 'Provide customer training on system operation, maintenance procedures, and monitoring system usage.',
                'status' => 'pending',
                'priority' => 'medium',
                'start_date' => now()->addDays(18),
                'due_date' => now()->addDays(20),
                'project_id' => $projects->random()->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->first()->id,
                'estimated_hours' => 4,
                'actual_hours' => 0,
            ],
            [
                'title' => 'Documentation and Warranty Registration',
                'description' => 'Complete all project documentation, warranty registration, and submit final reports to customer.',
                'status' => 'pending',
                'priority' => 'low',
                'start_date' => now()->addDays(20),
                'due_date' => now()->addDays(22),
                'project_id' => $projects->random()->id,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->first()->id,
                'estimated_hours' => 6,
                'actual_hours' => 0,
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::create($taskData);
        }
    }
}
