<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OMMaintenance;

class OMMaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maintenances = [
            [
                'maintenance_id' => 'MAINT-2025-0001',
                'project_name' => 'Solar Farm Project',
                'maintenance_type' => 'Preventive',
                'scheduled_date' => now()->addDays(3),
                'status' => 'scheduled',
                'technician_name' => 'Rajesh Kumar',
                'description' => 'Monthly preventive maintenance of solar panels',
                'cost' => 15000.00,
            ],
            [
                'maintenance_id' => 'MAINT-2025-0002',
                'project_name' => 'Residential Solar',
                'maintenance_type' => 'Corrective',
                'scheduled_date' => now()->subDays(1),
                'completed_date' => now()->subDays(1),
                'status' => 'completed',
                'technician_name' => 'Priya Sharma',
                'description' => 'Inverter repair and replacement',
                'work_performed' => 'Replaced faulty inverter module',
                'notes' => 'Customer satisfied with repair',
                'cost' => 25000.00,
            ],
            [
                'maintenance_id' => 'MAINT-2025-0003',
                'project_name' => 'Commercial Solar',
                'maintenance_type' => 'Emergency',
                'scheduled_date' => now(),
                'status' => 'in_progress',
                'technician_name' => 'John Doe',
                'description' => 'Emergency repair for power outage',
                'cost' => 30000.00,
            ],
        ];

        foreach ($maintenances as $maintenance) {
            OMMaintenance::create($maintenance);
        }
    }
}