<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectProfitability;
use App\Models\Project;
use App\Models\User;

class ProjectProfitabilitySeeder extends Seeder
{
    public function run()
    {
        $projects = Project::all();
        $users = User::all();

        if ($projects->isEmpty() || $users->isEmpty()) {
            return;
        }

        $statuses = ['draft', 'reviewed', 'approved', 'final'];
        $periods = ['monthly', 'quarterly', 'yearly'];

        // Create 8 profitability reports
        for ($i = 0; $i < 8; $i++) {
            $project = $projects->random();
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            $period = $periods[array_rand($periods)];

            // Generate dates based on period
            $startDate = now()->subDays(rand(30, 180));
            $days = match($period) {
                'monthly' => 30,
                'quarterly' => 90,
                'yearly' => 365
            };
            $endDate = $startDate->copy()->addDays($days);

            $contractValue = rand(50000, 500000);
            $progressBilling = rand(5000, 50000);
            $overrunRevenue = rand(0, 20000);

            $materialCosts = rand(15000, 150000);
            $laborCosts = rand(20000, 200000);
            $equipmentCosts = rand(5000, 50000);
            $transportationCosts = rand(2000, 20000);
            $permitsCosts = rand(1000, 10000);
            $overheadCosts = rand(5000, 50000);
            $subcontractorCosts = rand(10000, 100000);

            $totalRevenue = $contractValue + $progressBilling + $overrunRevenue;
            $totalCosts = $materialCosts + $laborCosts + $equipmentCosts + $transportationCosts + 
                         $permitsCosts + $overheadCosts + $subcontractorCosts;

            $grossProfit = $totalRevenue - $totalCosts;
            $grossMarginPercentage = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

            $changeOrderAmount = rand(0, 50000);
            $retentionAmount = rand(0, 25000);

            $totalDays = rand(30, 365);
            $daysCompleted = rand(10, $totalDays);
            $completionPercentage = ($daysCompleted / $totalDays) * 100;

            // Calculate net profit and margin
            $netProfit = $grossProfit - $retentionAmount;
            $netMarginPercentage = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

            ProjectProfitability::create([
                'project_id' => $project->id,
                'period' => $period,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),

                // Revenue fields
                'contract_value' => $contractValue,
                'progress_billing' => $progressBilling,
                'overrun_revenue' => $overrunRevenue,
                'total_revenue' => $totalRevenue,

                // Cost fields
                'material_costs' => $materialCosts,
                'labor_costs' => $laborCosts,
                'equipment_costs' => $equipmentCosts,
                'transportation_costs' => $transportationCosts,
                'permits_costs' => $permitsCosts,
                'overhead_costs' => $overheadCosts,
                'subcontractor_costs' => $subcontractorCosts,
                'total_costs' => $totalCosts,

                // Calculated fields
                'gross_profit' => $grossProfit,
                'gross_margin_percentage' => $grossMarginPercentage,
                'net_profit' => $netProfit,
                'net_margin_percentage' => $netMarginPercentage,

                // Additional fields
                'change_order_amount' => $changeOrderAmount,
                'retention_amount' => $retentionAmount,
                'days_completed' => $daysCompleted,
                'total_days' => $totalDays,
                'completion_percentage' => $completionPercentage,

                'status' => $status,
                'notes' => $this->getRandomNotes(),
                'created_by' => $user->id,
                
                // Add review info if status is reviewed or above
                'reviewed_by' => in_array($status, ['reviewed', 'approved', 'final']) ? $users->random()->id : null,
                'reviewed_at' => in_array($status, ['reviewed', 'approved', 'final']) ? now()->subDays(rand(1, 30)) : null,
            ]);
        }
    }

    private function getRandomNotes()
    {
        $notes = [
            'Project completed within budget. Excellent cost control.',
            'Some cost overruns due to material price increases.',
            'Labor costs came in higher than expected.',
            'Equipment rental costs were well managed.',
            'Change orders increased profitability significantly.',
            'Transportation costs were minimized through local sourcing.',
            'Permit fees were within expected range.',
            'Overhead allocation was accurate.',
            'Strong performance this quarter.',
            'Need to improve margin on next phase.',
        ];

        return $notes[array_rand($notes)];
    }
}
