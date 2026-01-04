<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialConsumption;
use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\Project;
use App\Models\User;

class MaterialConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = Material::with(['materialRequest'])->get();
        $projects = Project::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();

        // Activity types and descriptions
        $activityData = [
            'installation' => [
                'Solar panel mounting and wiring',
                'Electrical conduit installation',
                'Cable tray installation',
                'Foundation concrete pouring',
                'Structural beam installation',
                'Inverter mounting and connection',
                'Safety system installation',
                'Monitoring system setup',
            ],
            'maintenance' => [
                'Panel cleaning and inspection',
                'Connection maintenance',
                'System check and calibration',
                'Preventive maintenance tasks',
                'Component replacement',
                'Safety equipment inspection',
            ],
            'repair' => [
                'Panel damage repair',
                'Electrical fault correction',
                'Connection repair',
                'Structural damage restoration',
                'Equipment malfunction fixing',
            ],
            'testing' => [
                'System commissioning test',
                'Performance verification',
                'Safety system testing',
                'Electrical load testing',
                'Quality control inspection',
            ],
            'demo' => [
                'System demonstration',
                'Client presentation setup',
                'Training equipment preparation',
                'Exhibition display setup',
            ],
            'training' => [
                'Worker training session',
                'Safety training program',
                'Technical training workshop',
                'Equipment operation training',
            ],
        ];

        // Work phases with locations
        $workPhases = [
            'preparation' => ['Warehouse', 'Storage Area', 'Pre-production Area'],
            'foundation' => ['Site A - Foundation Area', 'Site B - Concrete Pouring Zone', 'Base Preparation Area'],
            'structure' => ['Main Structure Site', 'Beam Installation Zone', 'Framework Assembly Area'],
            'electrical' => ['Electrical Room', 'Panel Connection Area', 'Cable Routing Zone'],
            'commissioning' => ['Final Testing Area', 'System Commissioning Zone', 'Performance Test Site'],
            'maintenance' => ['Maintenance Floor', 'Equipment Service Area', 'Staging Area'],
            'other' => ['General Work Area', 'Support Facility', 'Administrative Zone'],
        ];

        $consumptionsData = [];

        // Generate consumption records for each material
        foreach ($materials->take(40) as $material) {
            $activityType = fake()->randomElement(array_keys($activityData));
            $workPhase = fake()->randomElement(array_keys($workPhases));
            $projectId = $material->materialRequest && $material->materialRequest->project_id 
                ? $material->materialRequest->project_id 
                : fake()->randomElement($projects);
            
            // Calculate realistic quantities (10% to 80% of available quantity)
            $maxQuantity = min($material->received_quantity, 100);
            $quantityConsumed = fake()->numberBetween(1, max(1, $maxQuantity));
            
            // Realistic consumption percentages based on activity
            $consumptionPercentage = match($activityType) {
                'testing', 'demo', 'training' => fake()->numberBetween(20, 60),
                'repair' => fake()->numberBetween(40, 80),
                'maintenance' => fake()->numberBetween(60, 90),
                'installation' => fake()->numberBetween(80, 98),
                default => fake()->numberBetween(70, 95),
            };
            
            // Wastage percentage (0% to 15% based on work type)
            $wastagePercentage = match($activityType) {
                'testing', 'demo', 'training' => fake()->numberBetween(0, 5),
                'repair' => fake()->numberBetween(5, 20),
                'installation' => fake()->numberBetween(2, 8),
                'maintenance' => fake()->numberBetween(0, 3),
                default => fake()->numberBetween(0, 10),
            };
            
            $returnPercentage = 100 - $consumptionPercentage - $wastagePercentage;
            
            // Quality status (biased towards good)
            $qualityStatus = fake()->randomElement([
                'good', 'good', 'good', 'good', 
                'damaged', 'defective', 'expired'
            ]);
            
            // Consumption status
            $consumptionStatus = fake()->randomElement([
                'completed', 'completed', 'completed',
                'in_progress', 'partial', 'damaged'
            ]);

            // Dates (last 3 months, skewed towards recent)
            $consumptionDate = fake()->dateTimeBetween('-3 months', 'now');
            
            $startTime = fake()->time('H:i');
            $endTimeHour = fake()->numberBetween($consumptionDate->format('H') + 1, 18);
            $endTime = sprintf('%02d:%02d', $endTimeHour, fake()->numberBetween(0, 59));

            $consumptionsData[] = [
                'consumption_number' => 'CONS-' . $consumptionDate->format('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'material_id' => $material->id,
                'material_request_id' => $material->material_request_id,
                'project_id' => $projectId,
                'activity_type' => $activityType,
                'activity_description' => fake()->randomElement($activityData[$activityType]),
                'work_phase' => $workPhase,
                'work_location' => fake()->randomElement($workPhases[$workPhase]),
                'quantity_consumed' => $quantityConsumed,
                'unit_of_measurement' => $material->unit ?: 'pieces',
                'consumption_percentage' => $consumptionPercentage,
                'wastage_percentage' => $wastagePercentage,
                'return_percentage' => max(0, $returnPercentage),
                'quality_status' => $qualityStatus,
                'consumption_status' => $consumptionStatus,
                'waste_disposed' => $wastagePercentage > 5,
                'return_to_stock' => $returnPercentage > 0,
                'unit_cost' => $material->unit_price,
                'total_cost' => $material->unit_price * $quantityConsumed,
                'wastage_cost' => $wastagePercentage > 0 ? ($material->unit_price * $quantityConsumed * $wastagePercentage / 100) : 0,
                'cost_center' => 'Project-' . $projectId . '-' . $workPhase,
                'consumption_date' => $consumptionDate->format('Y-m-d'),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => rand(0.5, 8),
                'documentation_type' => fake()->randomElement(['photo', 'receipt', 'report', 'video']),
                'documentation_path' => '/uploads/consumption/' . fake()->uuid() . '.jpg',
                'notes' => fake()->optional(0.6)->sentence(20),
                'quality_observations' => $qualityStatus !== 'good' ? fake()->sentence(15) : null,
                'consumed_by' => fake()->randomElement($users),
                'supervised_by' => fake()->optional(0.7)->randomElement($users),
                'approved_by' => $consumptionStatus === 'completed' ? fake()->randomElement($users) : null,
                'approved_at' => $consumptionStatus === 'completed' ? $consumptionDate->modify('+'.rand(1,3).' hours') : null,
            ];
        }

        // Create consumption records in batches
        foreach (array_chunk($consumptionsData, 50) as $chunk) {
            MaterialConsumption::insert($chunk);
        }
    }
}