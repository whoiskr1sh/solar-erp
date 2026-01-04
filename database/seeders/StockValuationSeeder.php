<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StockValuation;

class StockValuationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stockValuations = [
            [
                'category' => 'Solar Panels',
                'item_name' => 'Solar Panel 320W Monocrystalline',
                'item_code' => 'SP-320-MC-001',
                'quantity' => 2500,
                'unit_cost' => 8500.00,
                'total_value' => 21250000.00,
                'warehouse' => 'Main Warehouse',
                'last_updated' => now()->subDays(2),
            ],
            [
                'category' => 'Solar Panels',
                'item_name' => 'Solar Panel 400W Polycrystalline',
                'item_code' => 'SP-400-PC-001',
                'quantity' => 1800,
                'unit_cost' => 7200.00,
                'total_value' => 12960000.00,
                'warehouse' => 'Main Warehouse',
                'last_updated' => now()->subDays(1),
            ],
            [
                'category' => 'Inverters',
                'item_name' => 'String Inverter 5KW',
                'item_code' => 'INV-5K-SI-001',
                'quantity' => 150,
                'unit_cost' => 25000.00,
                'total_value' => 3750000.00,
                'warehouse' => 'Main Warehouse',
                'last_updated' => now()->subDays(3),
            ],
            [
                'category' => 'Inverters',
                'item_name' => 'Central Inverter 100KW',
                'item_code' => 'INV-100K-CI-001',
                'quantity' => 25,
                'unit_cost' => 150000.00,
                'total_value' => 3750000.00,
                'warehouse' => 'Secondary Warehouse',
                'last_updated' => now()->subDays(5),
            ],
            [
                'category' => 'Mounting Systems',
                'item_name' => 'Ground Mount Structure',
                'item_code' => 'MS-GM-001',
                'quantity' => 500,
                'unit_cost' => 12000.00,
                'total_value' => 6000000.00,
                'warehouse' => 'Secondary Warehouse',
                'last_updated' => now()->subDays(4),
            ],
            [
                'category' => 'Mounting Systems',
                'item_name' => 'Roof Mount Structure',
                'item_code' => 'MS-RM-001',
                'quantity' => 300,
                'unit_cost' => 8000.00,
                'total_value' => 2400000.00,
                'warehouse' => 'Main Warehouse',
                'last_updated' => now()->subDays(6),
            ],
            [
                'category' => 'Cables & Accessories',
                'item_name' => 'DC Cable 4mm²',
                'item_code' => 'CAB-DC-4MM-001',
                'quantity' => 10000,
                'unit_cost' => 150.00,
                'total_value' => 1500000.00,
                'warehouse' => 'Main Warehouse',
                'last_updated' => now()->subDays(1),
            ],
            [
                'category' => 'Cables & Accessories',
                'item_name' => 'AC Cable 16mm²',
                'item_code' => 'CAB-AC-16MM-001',
                'quantity' => 5000,
                'unit_cost' => 300.00,
                'total_value' => 1500000.00,
                'warehouse' => 'Secondary Warehouse',
                'last_updated' => now()->subDays(2),
            ],
        ];

        foreach ($stockValuations as $valuation) {
            StockValuation::create($valuation);
        }
    }
}