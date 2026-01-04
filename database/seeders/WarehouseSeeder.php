<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'warehouse_code' => 'WH-001',
                'warehouse_name' => 'Main Warehouse',
                'location' => 'Mumbai, Maharashtra',
                'manager_name' => 'Rajesh Kumar',
                'manager_email' => 'rajesh@company.com',
                'manager_phone' => '+91-9876543210',
                'status' => 'active',
                'capacity_percentage' => 85,
                'total_items' => 245,
                'description' => 'Main warehouse for solar equipment and materials',
            ],
            [
                'warehouse_code' => 'WH-002',
                'warehouse_name' => 'Secondary Warehouse',
                'location' => 'Delhi, NCR',
                'manager_name' => 'Priya Sharma',
                'manager_email' => 'priya@company.com',
                'manager_phone' => '+91-9876543211',
                'status' => 'active',
                'capacity_percentage' => 65,
                'total_items' => 180,
                'description' => 'Secondary warehouse for regional distribution',
            ],
            [
                'warehouse_code' => 'WH-003',
                'warehouse_name' => 'Equipment Warehouse',
                'location' => 'Bangalore, Karnataka',
                'manager_name' => 'John Doe',
                'manager_email' => 'john@company.com',
                'manager_phone' => '+91-9876543212',
                'status' => 'maintenance',
                'capacity_percentage' => 45,
                'total_items' => 120,
                'description' => 'Specialized warehouse for heavy equipment',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}