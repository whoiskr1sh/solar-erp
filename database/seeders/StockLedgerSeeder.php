<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StockLedger;

class StockLedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stockLedgers = [
            [
                'transaction_date' => now()->subDays(1),
                'transaction_time' => '10:30:00',
                'item_name' => 'Solar Panel 320W Monocrystalline',
                'item_code' => 'SP-320-MC-001',
                'transaction_type' => 'inward',
                'reference_number' => 'GRN-2025-0001',
                'inward_quantity' => 100,
                'outward_quantity' => 0,
                'balance_quantity' => 100,
                'warehouse' => 'Main Warehouse',
            ],
            [
                'transaction_date' => now()->subDays(1),
                'transaction_time' => '14:15:00',
                'item_name' => 'Solar Panel 320W Monocrystalline',
                'item_code' => 'SP-320-MC-001',
                'transaction_type' => 'outward',
                'reference_number' => 'DC-2025-0001',
                'inward_quantity' => 0,
                'outward_quantity' => 25,
                'balance_quantity' => 75,
                'warehouse' => 'Main Warehouse',
            ],
            [
                'transaction_date' => now()->subDays(2),
                'transaction_time' => '09:45:00',
                'item_name' => 'String Inverter 5KW',
                'item_code' => 'INV-5K-SI-001',
                'transaction_type' => 'inward',
                'reference_number' => 'GRN-2025-0002',
                'inward_quantity' => 50,
                'outward_quantity' => 0,
                'balance_quantity' => 50,
                'warehouse' => 'Main Warehouse',
            ],
            [
                'transaction_date' => now()->subDays(2),
                'transaction_time' => '16:20:00',
                'item_name' => 'String Inverter 5KW',
                'item_code' => 'INV-5K-SI-001',
                'transaction_type' => 'outward',
                'reference_number' => 'DC-2025-0002',
                'inward_quantity' => 0,
                'outward_quantity' => 10,
                'balance_quantity' => 40,
                'warehouse' => 'Main Warehouse',
            ],
            [
                'transaction_date' => now()->subDays(3),
                'transaction_time' => '11:00:00',
                'item_name' => 'Ground Mount Structure',
                'item_code' => 'MS-GM-001',
                'transaction_type' => 'inward',
                'reference_number' => 'GRN-2025-0003',
                'inward_quantity' => 200,
                'outward_quantity' => 0,
                'balance_quantity' => 200,
                'warehouse' => 'Secondary Warehouse',
            ],
            [
                'transaction_date' => now()->subDays(3),
                'transaction_time' => '15:30:00',
                'item_name' => 'Ground Mount Structure',
                'item_code' => 'MS-GM-001',
                'transaction_type' => 'transfer',
                'reference_number' => 'TR-2025-0001',
                'inward_quantity' => 0,
                'outward_quantity' => 50,
                'balance_quantity' => 150,
                'warehouse' => 'Secondary Warehouse',
            ],
            [
                'transaction_date' => now()->subDays(4),
                'transaction_time' => '08:15:00',
                'item_name' => 'DC Cable 4mm²',
                'item_code' => 'CAB-DC-4MM-001',
                'transaction_type' => 'inward',
                'reference_number' => 'GRN-2025-0004',
                'inward_quantity' => 2000,
                'outward_quantity' => 0,
                'balance_quantity' => 2000,
                'warehouse' => 'Main Warehouse',
            ],
            [
                'transaction_date' => now()->subDays(4),
                'transaction_time' => '13:45:00',
                'item_name' => 'DC Cable 4mm²',
                'item_code' => 'CAB-DC-4MM-001',
                'transaction_type' => 'outward',
                'reference_number' => 'DC-2025-0003',
                'inward_quantity' => 0,
                'outward_quantity' => 500,
                'balance_quantity' => 1500,
                'warehouse' => 'Main Warehouse',
            ],
        ];

        foreach ($stockLedgers as $ledger) {
            StockLedger::create($ledger);
        }
    }
}