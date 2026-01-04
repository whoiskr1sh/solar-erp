<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::take(5)->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        $sampleNotifications = [
            [
                'user_id' => $users->random()->id,
                'type' => 'project_update',
                'title' => 'Project Status Update',
                'message' => 'Solar Panel Installation Project has been updated to "In Progress" status.',
                'data' => json_encode(['project_id' => 1, 'status' => 'in_progress']),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'task_assigned',
                'title' => 'New Task Assigned',
                'message' => 'You have been assigned a new task: "Site Survey for Commercial Project".',
                'data' => json_encode(['task_id' => 1, 'project_id' => 2]),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(4),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'dpr_approval',
                'title' => 'DPR Approval Required',
                'message' => 'Daily Progress Report for "Residential Solar Installation" is pending your approval.',
                'data' => json_encode(['dpr_id' => 1, 'project_id' => 1]),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'milestone_achieved',
                'title' => 'Milestone Achieved',
                'message' => 'Congratulations! Project "Solar Farm Development" has achieved 50% completion milestone.',
                'data' => json_encode(['project_id' => 3, 'milestone' => '50% completion']),
                'read_at' => Carbon::now()->subHours(1),
                'created_at' => Carbon::now()->subHours(8),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'budget_alert',
                'title' => 'Budget Alert',
                'message' => 'Project "Commercial Solar Installation" has exceeded 80% of allocated budget.',
                'data' => json_encode(['project_id' => 2, 'budget_used' => '80%']),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(10),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'material_request',
                'title' => 'Material Request',
                'message' => 'New material request for "Solar Panels (50 units)" requires your approval.',
                'data' => json_encode(['request_id' => 1, 'material' => 'Solar Panels']),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(12),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'deadline_reminder',
                'title' => 'Deadline Reminder',
                'message' => 'Task "Installation Planning" is due in 2 days. Please complete it on time.',
                'data' => json_encode(['task_id' => 2, 'deadline' => Carbon::now()->addDays(2)]),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(14),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'system_update',
                'title' => 'System Update',
                'message' => 'New features have been added to the Solar ERP system. Check out the updated dashboard.',
                'data' => json_encode(['version' => '2.1.0', 'features' => ['Gantt Chart', 'DPR System']]),
                'read_at' => Carbon::now()->subMinutes(30),
                'created_at' => Carbon::now()->subHours(16),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'expense_approval',
                'title' => 'Expense Approval',
                'message' => 'Expense claim of ₹15,000 for "Equipment Rental" is pending your approval.',
                'data' => json_encode(['expense_id' => 1, 'amount' => 15000]),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(18),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'quality_check',
                'title' => 'Quality Check Required',
                'message' => 'Quality inspection is required for completed work at "Solar Panel Installation Site".',
                'data' => json_encode(['project_id' => 1, 'location' => 'Site A']),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(20),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'team_meeting',
                'title' => 'Team Meeting Scheduled',
                'message' => 'Weekly project review meeting is scheduled for tomorrow at 10:00 AM.',
                'data' => json_encode(['meeting_id' => 1, 'time' => '10:00 AM']),
                'read_at' => Carbon::now()->subMinutes(15),
                'created_at' => Carbon::now()->subHours(22),
            ],
            [
                'user_id' => $users->random()->id,
                'type' => 'safety_alert',
                'title' => 'Safety Alert',
                'message' => 'Safety inspection required: Workers not wearing helmets at construction site.',
                'data' => json_encode(['site_id' => 1, 'issue' => 'Safety equipment']),
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(24),
            ],
        ];

        foreach ($sampleNotifications as $notificationData) {
            Notification::create($notificationData);
        }

        $this->command->info('Notifications seeded successfully!');
    }
}