<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResourceAllocation;
use App\Models\Project;
use App\Models\Activity;
use App\Models\User;

class ResourceAllocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $activities = Activity::all();
        $users = User::all();

        if ($projects->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No projects or users found. Please run ProjectSeeder and UserSeeder first.');
            return;
        }

        $allocations = [
            [
                'title' => 'Senior Solar Engineer Allocation',
                'description' => 'Allocation of senior solar engineer for project design and supervision',
                'resource_type' => 'human',
                'priority' => 'high',
                'resource_name' => 'John Smith',
                'resource_category' => 'Engineering',
                'resource_specifications' => '5+ years experience in solar project design, AutoCAD certified',
                'allocated_quantity' => 1,
                'quantity_unit' => 'person',
                'hourly_rate' => 1500.00,
                'total_estimated_cost' => 120000.00,
                'budget_allocated' => 150000.00,
                'utilization_percentage' => 75.00,
                'notes' => 'Key resource for project success',
                'tags' => ['engineering', 'senior', 'design'],
                'is_critical' => true,
                'is_billable' => true,
            ],
            [
                'title' => 'Solar Panel Installation Equipment',
                'description' => 'Equipment allocation for solar panel installation work',
                'resource_type' => 'equipment',
                'priority' => 'medium',
                'resource_name' => 'Solar Panel Lifting Equipment',
                'resource_category' => 'Installation Tools',
                'resource_specifications' => 'Hydraulic lifting system, capacity 500kg, safety certified',
                'allocated_quantity' => 2,
                'quantity_unit' => 'units',
                'unit_cost' => 5000.00,
                'total_estimated_cost' => 10000.00,
                'budget_allocated' => 12000.00,
                'utilization_percentage' => 60.00,
                'notes' => 'Essential for large panel installations',
                'tags' => ['equipment', 'installation', 'safety'],
                'is_critical' => false,
                'is_billable' => true,
            ],
            [
                'title' => 'Solar Panel Materials',
                'description' => 'Allocation of solar panels and mounting materials',
                'resource_type' => 'material',
                'priority' => 'critical',
                'resource_name' => 'Monocrystalline Solar Panels 400W',
                'resource_category' => 'Solar Components',
                'resource_specifications' => '400W monocrystalline panels, 20.5% efficiency, 25-year warranty',
                'allocated_quantity' => 100,
                'quantity_unit' => 'panels',
                'unit_cost' => 12000.00,
                'total_estimated_cost' => 1200000.00,
                'budget_allocated' => 1300000.00,
                'utilization_percentage' => 90.00,
                'notes' => 'Main project materials',
                'tags' => ['panels', 'materials', 'critical'],
                'is_critical' => true,
                'is_billable' => true,
            ],
            [
                'title' => 'Project Budget Allocation',
                'description' => 'Financial allocation for project expenses',
                'resource_type' => 'financial',
                'priority' => 'high',
                'resource_name' => 'Project Budget Fund',
                'resource_category' => 'Finance',
                'resource_specifications' => 'Working capital for project operations',
                'allocated_quantity' => 1,
                'quantity_unit' => 'fund',
                'unit_cost' => 5000000.00,
                'total_estimated_cost' => 5000000.00,
                'budget_allocated' => 5500000.00,
                'utilization_percentage' => 45.00,
                'notes' => 'Main project funding',
                'tags' => ['budget', 'finance', 'funding'],
                'is_critical' => true,
                'is_billable' => false,
            ],
            [
                'title' => 'Site Supervisor Allocation',
                'description' => 'Site supervisor for daily operations management',
                'resource_type' => 'human',
                'priority' => 'medium',
                'resource_name' => 'Mike Johnson',
                'resource_category' => 'Supervision',
                'resource_specifications' => '10+ years construction experience, safety certified',
                'allocated_quantity' => 1,
                'quantity_unit' => 'person',
                'hourly_rate' => 800.00,
                'total_estimated_cost' => 64000.00,
                'budget_allocated' => 70000.00,
                'utilization_percentage' => 85.00,
                'notes' => 'Daily site management',
                'tags' => ['supervision', 'management', 'site'],
                'is_critical' => false,
                'is_billable' => true,
            ],
            [
                'title' => 'Inverter Equipment',
                'description' => 'Solar inverters for power conversion',
                'resource_type' => 'equipment',
                'priority' => 'high',
                'resource_name' => 'String Inverters 50kW',
                'resource_category' => 'Power Electronics',
                'resource_specifications' => '50kW string inverters, 98% efficiency, IP65 rating',
                'allocated_quantity' => 4,
                'quantity_unit' => 'units',
                'unit_cost' => 150000.00,
                'total_estimated_cost' => 600000.00,
                'budget_allocated' => 650000.00,
                'utilization_percentage' => 70.00,
                'notes' => 'Critical for power conversion',
                'tags' => ['inverters', 'power', 'electronics'],
                'is_critical' => true,
                'is_billable' => true,
            ],
            [
                'title' => 'Mounting Structure Materials',
                'description' => 'Steel mounting structures for solar panels',
                'resource_type' => 'material',
                'priority' => 'medium',
                'resource_name' => 'Galvanized Steel Mounting Structure',
                'resource_category' => 'Structural Components',
                'resource_specifications' => 'Hot-dip galvanized steel, corrosion resistant, 25-year warranty',
                'allocated_quantity' => 50,
                'quantity_unit' => 'sets',
                'unit_cost' => 25000.00,
                'total_estimated_cost' => 1250000.00,
                'budget_allocated' => 1300000.00,
                'utilization_percentage' => 55.00,
                'notes' => 'Structural support for panels',
                'tags' => ['mounting', 'steel', 'structural'],
                'is_critical' => false,
                'is_billable' => true,
            ],
            [
                'title' => 'Quality Control Inspector',
                'description' => 'QC inspector for quality assurance',
                'resource_type' => 'human',
                'priority' => 'medium',
                'resource_name' => 'Sarah Wilson',
                'resource_category' => 'Quality Control',
                'resource_specifications' => 'ISO 9001 certified, 8+ years QC experience',
                'allocated_quantity' => 1,
                'quantity_unit' => 'person',
                'hourly_rate' => 1000.00,
                'total_estimated_cost' => 80000.00,
                'budget_allocated' => 85000.00,
                'utilization_percentage' => 65.00,
                'notes' => 'Quality assurance specialist',
                'tags' => ['quality', 'inspection', 'certified'],
                'is_critical' => false,
                'is_billable' => true,
            ],
        ];

        foreach ($allocations as $index => $allocationData) {
            $project = $projects->random();
            $activity = $activities->isNotEmpty() ? $activities->random() : null;
            $allocatedTo = $users->random();
            $allocatedBy = $users->random();

            ResourceAllocation::create([
                'allocation_code' => ResourceAllocation::generateAllocationCode(),
                'title' => $allocationData['title'],
                'description' => $allocationData['description'],
                'resource_type' => $allocationData['resource_type'],
                'priority' => $allocationData['priority'],
                'status' => ['planned', 'allocated', 'in_use', 'completed'][array_rand(['planned', 'allocated', 'in_use', 'completed'])],
                'project_id' => $project->id,
                'activity_id' => $activity ? $activity->id : null,
                'resource_name' => $allocationData['resource_name'],
                'resource_category' => $allocationData['resource_category'],
                'resource_specifications' => $allocationData['resource_specifications'],
                'allocated_to' => $allocationData['resource_type'] === 'human' ? $allocatedTo->id : null,
                'allocated_by' => $allocatedBy->id,
                'allocation_start_date' => now()->subDays(rand(1, 30)),
                'allocation_end_date' => now()->addDays(rand(30, 90)),
                'allocated_quantity' => $allocationData['allocated_quantity'],
                'quantity_unit' => $allocationData['quantity_unit'],
                'hourly_rate' => $allocationData['hourly_rate'] ?? 0,
                'unit_cost' => $allocationData['unit_cost'] ?? 0,
                'total_estimated_cost' => $allocationData['total_estimated_cost'],
                'total_actual_cost' => $allocationData['total_estimated_cost'] * (rand(80, 120) / 100),
                'budget_allocated' => $allocationData['budget_allocated'],
                'utilization_percentage' => $allocationData['utilization_percentage'],
                'utilization_notes' => 'Sample utilization data',
                'notes' => $allocationData['notes'],
                'tags' => $allocationData['tags'],
                'is_critical' => $allocationData['is_critical'],
                'is_billable' => $allocationData['is_billable'],
            ]);
        }

        $this->command->info('Resource allocations seeded successfully!');
    }
}
