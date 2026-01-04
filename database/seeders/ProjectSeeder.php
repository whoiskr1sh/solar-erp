<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Lead;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        // Get interested leads as clients (since 'converted' status doesn't exist anymore)
        $clients = Lead::where('status', 'interested')->get();
        
        // Clear existing projects to prevent duplicates
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('projects')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $projects = [
            [
                'name' => 'Solar Installation - TechCorp Industries',
                'description' => 'Complete rooftop solar installation for TechCorp Industries office building in Bangalore. Includes 100kW solar panels, inverters, and monitoring system.',
                'project_code' => 'PROJ-2024-001',
                'status' => 'active',
                'priority' => 'high',
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(60),
                'budget' => 750000,
                'actual_cost' => 250000,
                'project_manager_id' => $users->first()->id,
                'client_id' => $clients->first()->id ?? null,
                'created_by' => $users->first()->id,
                'location' => 'IT Park, Bangalore, Karnataka',
                'milestones' => [
                    ['name' => 'Site Survey', 'date' => now()->subDays(25), 'status' => 'completed'],
                    ['name' => 'Design Approval', 'date' => now()->subDays(20), 'status' => 'completed'],
                    ['name' => 'Material Procurement', 'date' => now()->subDays(10), 'status' => 'completed'],
                    ['name' => 'Installation', 'date' => now()->addDays(30), 'status' => 'in_progress'],
                    ['name' => 'Testing & Commissioning', 'date' => now()->addDays(50), 'status' => 'pending'],
                ],
            ],
            [
                'name' => 'Residential Solar System - Sunita Singh',
                'description' => '3kW residential solar installation for 3BHK house in Delhi. Includes rooftop mounting, grid-tie inverter, and net metering setup.',
                'project_code' => 'PROJ-2024-002',
                'status' => 'planning',
                'priority' => 'medium',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(25),
                'budget' => 250000,
                'actual_cost' => 0,
                'project_manager_id' => $users->skip(1)->first()->id ?? $users->first()->id,
                'client_id' => $clients->skip(1)->first()->id ?? null,
                'created_by' => $users->first()->id,
                'location' => 'Residential Colony, Delhi',
                'milestones' => [
                    ['name' => 'Site Survey', 'date' => now()->addDays(2), 'status' => 'pending'],
                    ['name' => 'Design & Approval', 'date' => now()->addDays(7), 'status' => 'pending'],
                    ['name' => 'Installation', 'date' => now()->addDays(15), 'status' => 'pending'],
                    ['name' => 'Commissioning', 'date' => now()->addDays(22), 'status' => 'pending'],
                ],
            ],
            [
                'name' => 'Industrial Solar Plant - Mehta Manufacturing',
                'description' => '500kW industrial solar power plant for Mehta Manufacturing facility in Pune. Large scale ground-mounted installation with advanced monitoring.',
                'project_code' => 'PROJ-2024-003',
                'status' => 'planning',
                'priority' => 'urgent',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(120),
                'budget' => 2000000,
                'actual_cost' => 0,
                'project_manager_id' => $users->first()->id,
                'client_id' => $clients->skip(2)->first()->id ?? null,
                'created_by' => $users->first()->id,
                'location' => 'Industrial Zone, Pune, Maharashtra',
                'milestones' => [
                    ['name' => 'Feasibility Study', 'date' => now()->addDays(5), 'status' => 'pending'],
                    ['name' => 'Contract Signing', 'date' => now()->addDays(10), 'status' => 'pending'],
                    ['name' => 'Site Preparation', 'date' => now()->addDays(20), 'status' => 'pending'],
                    ['name' => 'Panel Installation', 'date' => now()->addDays(60), 'status' => 'pending'],
                    ['name' => 'Grid Connection', 'date' => now()->addDays(100), 'status' => 'pending'],
                    ['name' => 'Commissioning', 'date' => now()->addDays(115), 'status' => 'pending'],
                ],
            ],
            [
                'name' => 'Hospital Solar Backup - City Hospital',
                'description' => 'Emergency solar backup system for City Hospital in Hyderabad. Includes battery storage and automatic switching for critical medical equipment.',
                'project_code' => 'PROJ-2024-004',
                'status' => 'completed',
                'priority' => 'high',
                'start_date' => now()->subDays(90),
                'end_date' => now()->subDays(10),
                'budget' => 800000,
                'actual_cost' => 780000,
                'project_manager_id' => $users->skip(1)->first()->id ?? $users->first()->id,
                'client_id' => $clients->skip(3)->first()->id ?? null,
                'created_by' => $users->first()->id,
                'location' => 'Medical District, Hyderabad, Telangana',
                'milestones' => [
                    ['name' => 'Site Survey', 'date' => now()->subDays(85), 'status' => 'completed'],
                    ['name' => 'Design Approval', 'date' => now()->subDays(75), 'status' => 'completed'],
                    ['name' => 'Installation', 'date' => now()->subDays(45), 'status' => 'completed'],
                    ['name' => 'Testing', 'date' => now()->subDays(20), 'status' => 'completed'],
                    ['name' => 'Commissioning', 'date' => now()->subDays(10), 'status' => 'completed'],
                ],
            ],
            [
                'name' => 'School Solar Initiative - Delhi Public School',
                'description' => 'Educational solar installation for Delhi Public School. Includes solar panels, educational displays, and student learning modules.',
                'project_code' => 'PROJ-2024-005',
                'status' => 'on_hold',
                'priority' => 'low',
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(45),
                'budget' => 400000,
                'actual_cost' => 50000,
                'project_manager_id' => $users->first()->id,
                'client_id' => null, // This was a lost lead
                'created_by' => $users->first()->id,
                'location' => 'Education Hub, Delhi',
                'milestones' => [
                    ['name' => 'Site Survey', 'date' => now()->subDays(10), 'status' => 'completed'],
                    ['name' => 'Design', 'date' => now()->addDays(5), 'status' => 'pending'],
                    ['name' => 'Installation', 'date' => now()->addDays(25), 'status' => 'pending'],
                    ['name' => 'Commissioning', 'date' => now()->addDays(40), 'status' => 'pending'],
                ],
            ],
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }
    }
}
