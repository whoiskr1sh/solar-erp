<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteWarehouse;
use App\Models\Project;
use App\Models\User;

class SiteWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::take(5)->get();
        $managers = User::whereIn('department', ['Management', 'Operations', 'Project Management'])->take(3)->get();
        
        if ($projects->isEmpty() || $managers->isEmpty()) {
            $this->command->warn('No projects or managers found. Please seed projects and users first.');
            return;
        }

        $sampleWarehouses = [
            [
                'project_id' => $projects->random()->id,
                'warehouse_name' => 'Main Storage Facility',
                'location' => 'Site A - Block 1',
                'address' => 'Industrial Area, Sector 15, Gurgaon, Haryana 122001',
                'contact_person' => 'Rajesh Kumar',
                'contact_phone' => '+91-9876543210',
                'contact_email' => 'rajesh.kumar@solarerp.com',
                'total_capacity' => 5000.00,
                'used_capacity' => 3200.00,
                'status' => 'active',
                'description' => 'Primary storage facility for solar panels, inverters, and electrical components. Equipped with climate control and security systems.',
                'facilities' => ['Security', 'CCTV', 'Fire Safety', 'Climate Control', 'Inventory Management'],
                'managed_by' => $managers->random()->id,
            ],
            [
                'project_id' => $projects->random()->id,
                'warehouse_name' => 'Equipment Storage',
                'location' => 'Site B - Warehouse 2',
                'address' => 'Commercial Complex, Phase 2, Noida, Uttar Pradesh 201301',
                'contact_person' => 'Priya Sharma',
                'contact_phone' => '+91-9876543211',
                'contact_email' => 'priya.sharma@solarerp.com',
                'total_capacity' => 3000.00,
                'used_capacity' => 1800.00,
                'status' => 'active',
                'description' => 'Storage facility for heavy equipment, tools, and construction materials. Features loading dock and crane facilities.',
                'facilities' => ['Loading Dock', 'Crane', 'Security', 'Fire Safety', 'Access Control'],
                'managed_by' => $managers->random()->id,
            ],
            [
                'project_id' => $projects->random()->id,
                'warehouse_name' => 'Material Warehouse',
                'location' => 'Site C - Storage Unit',
                'address' => 'Business Park, Sector 62, Gurgaon, Haryana 122102',
                'contact_person' => 'Amit Singh',
                'contact_phone' => '+91-9876543212',
                'contact_email' => 'amit.singh@solarerp.com',
                'total_capacity' => 4000.00,
                'used_capacity' => 2500.00,
                'status' => 'active',
                'description' => 'Dedicated storage for raw materials, cables, mounting structures, and consumables.',
                'facilities' => ['Inventory Management', 'Security', 'CCTV', 'Fire Safety', 'Maintenance'],
                'managed_by' => $managers->random()->id,
            ],
            [
                'project_id' => $projects->random()->id,
                'warehouse_name' => 'Temporary Storage',
                'location' => 'Site D - Temp Facility',
                'address' => 'Industrial Zone, Phase 3, Faridabad, Haryana 121003',
                'contact_person' => 'Sunita Verma',
                'contact_phone' => '+91-9876543213',
                'contact_email' => 'sunita.verma@solarerp.com',
                'total_capacity' => 2000.00,
                'used_capacity' => 800.00,
                'status' => 'maintenance',
                'description' => 'Temporary storage facility currently under maintenance. Used for short-term material storage.',
                'facilities' => ['Security', 'Fire Safety'],
                'managed_by' => $managers->random()->id,
            ],
            [
                'project_id' => $projects->random()->id,
                'warehouse_name' => 'Finished Goods Storage',
                'location' => 'Site E - Quality Control',
                'address' => 'Export Processing Zone, Sector 25, Gurgaon, Haryana 122001',
                'contact_person' => 'Vikram Patel',
                'contact_phone' => '+91-9876543214',
                'contact_email' => 'vikram.patel@solarerp.com',
                'total_capacity' => 3500.00,
                'used_capacity' => 2100.00,
                'status' => 'active',
                'description' => 'Storage facility for finished solar products ready for dispatch. Includes quality control area.',
                'facilities' => ['Quality Control', 'Security', 'CCTV', 'Climate Control', 'Inventory Management', 'Insurance'],
                'managed_by' => $managers->random()->id,
            ],
            [
                'project_id' => $projects->random()->id,
                'warehouse_name' => 'Emergency Storage',
                'location' => 'Site F - Backup Facility',
                'address' => 'Warehouse Complex, Sector 18, Gurgaon, Haryana 122015',
                'contact_person' => 'Neha Gupta',
                'contact_phone' => '+91-9876543215',
                'contact_email' => 'neha.gupta@solarerp.com',
                'total_capacity' => 1500.00,
                'used_capacity' => 0.00,
                'status' => 'inactive',
                'description' => 'Backup storage facility kept ready for emergency situations or overflow storage needs.',
                'facilities' => ['Security', 'Fire Safety', 'Access Control'],
                'managed_by' => $managers->random()->id,
            ],
        ];

        foreach ($sampleWarehouses as $warehouseData) {
            SiteWarehouse::create($warehouseData);
        }

        $this->command->info('Site Warehouses seeded successfully!');
    }
}