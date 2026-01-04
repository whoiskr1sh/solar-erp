<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Costing;
use App\Models\User;
use App\Models\Project;

class CostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $projects = Project::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $sampleCostings = [
            [
                'project_name' => 'Solar Panel Installation - Residential',
                'client_name' => 'Rajesh Kumar',
                'client_email' => 'rajesh.kumar@email.com',
                'client_phone' => '+91 98765 43210',
                'project_description' => 'Complete solar panel installation for residential property including panels, inverter, and battery backup system.',
                'location' => 'Delhi, India',
                'material_cost' => 150000,
                'labor_cost' => 25000,
                'equipment_cost' => 15000,
                'transportation_cost' => 5000,
                'overhead_cost' => 10000,
                'profit_margin' => 15,
                'tax_rate' => 18,
                'discount' => 5000,
                'status' => 'approved',
                'validity_date' => now()->addDays(30),
                'notes' => 'High priority project with premium components.',
            ],
            [
                'project_name' => 'Commercial Solar Farm Setup',
                'client_name' => 'Green Energy Solutions Pvt Ltd',
                'client_email' => 'contact@greenenergy.com',
                'client_phone' => '+91 98765 43211',
                'project_description' => 'Large scale commercial solar farm installation with 500kW capacity including grid connection.',
                'location' => 'Rajasthan, India',
                'material_cost' => 2500000,
                'labor_cost' => 200000,
                'equipment_cost' => 150000,
                'transportation_cost' => 50000,
                'overhead_cost' => 100000,
                'profit_margin' => 12,
                'tax_rate' => 18,
                'discount' => 100000,
                'status' => 'pending',
                'validity_date' => now()->addDays(45),
                'notes' => 'Government tender project with strict compliance requirements.',
            ],
            [
                'project_name' => 'Solar Water Heater Installation',
                'client_name' => 'Priya Sharma',
                'client_email' => 'priya.sharma@email.com',
                'client_phone' => '+91 98765 43212',
                'project_description' => 'Solar water heater installation for residential use with 200L capacity.',
                'location' => 'Mumbai, India',
                'material_cost' => 45000,
                'labor_cost' => 8000,
                'equipment_cost' => 3000,
                'transportation_cost' => 2000,
                'overhead_cost' => 3000,
                'profit_margin' => 20,
                'tax_rate' => 18,
                'discount' => 2000,
                'status' => 'draft',
                'validity_date' => now()->addDays(15),
                'notes' => 'Standard residential installation.',
            ],
            [
                'project_name' => 'Industrial Solar Power Plant',
                'client_name' => 'Industrial Solutions Ltd',
                'client_email' => 'projects@industrialsolutions.com',
                'client_phone' => '+91 98765 43213',
                'project_description' => '1MW industrial solar power plant with battery storage and monitoring system.',
                'location' => 'Gujarat, India',
                'material_cost' => 4500000,
                'labor_cost' => 300000,
                'equipment_cost' => 200000,
                'transportation_cost' => 80000,
                'overhead_cost' => 150000,
                'profit_margin' => 10,
                'tax_rate' => 18,
                'discount' => 200000,
                'status' => 'approved',
                'validity_date' => now()->addDays(60),
                'notes' => 'Large scale industrial project with advanced monitoring.',
            ],
            [
                'project_name' => 'Solar Street Light Installation',
                'client_name' => 'Municipal Corporation',
                'client_email' => 'projects@municipal.gov.in',
                'client_phone' => '+91 98765 43214',
                'project_description' => 'Installation of 100 solar street lights across the city with automatic controls.',
                'location' => 'Bangalore, India',
                'material_cost' => 800000,
                'labor_cost' => 120000,
                'equipment_cost' => 50000,
                'transportation_cost' => 30000,
                'overhead_cost' => 40000,
                'profit_margin' => 8,
                'tax_rate' => 18,
                'discount' => 50000,
                'status' => 'pending',
                'validity_date' => now()->addDays(30),
                'notes' => 'Government project with specific quality standards.',
            ],
            [
                'project_name' => 'Solar Pump Installation',
                'client_name' => 'Farmers Cooperative Society',
                'client_email' => 'info@farmerscoop.com',
                'client_phone' => '+91 98765 43215',
                'project_description' => 'Solar water pump installation for agricultural irrigation with 5HP capacity.',
                'location' => 'Punjab, India',
                'material_cost' => 180000,
                'labor_cost' => 25000,
                'equipment_cost' => 12000,
                'transportation_cost' => 8000,
                'overhead_cost' => 15000,
                'profit_margin' => 18,
                'tax_rate' => 18,
                'discount' => 10000,
                'status' => 'draft',
                'validity_date' => now()->addDays(20),
                'notes' => 'Agricultural project with subsidy benefits.',
            ],
        ];

        foreach ($sampleCostings as $index => $costingData) {
            $costing = new Costing($costingData);
            $costing->costing_number = $costing->generateCostingNumber();
            $costing->created_by = $users->random()->id;
            
            // Link to project if available
            if ($projects->isNotEmpty() && $index < $projects->count()) {
                $costing->project_id = $projects[$index]->id;
            }
            
            // Calculate total cost
            $costing->total_cost = $costing->calculateTotalCost();
            
            // Set approval details for approved costings
            if ($costing->status === 'approved') {
                $costing->approved_by = $users->random()->id;
                $costing->approved_at = now()->subDays(rand(1, 30));
            }
            
            $costing->save();
        }

        $this->command->info('CostingSeeder completed successfully!');
    }
}