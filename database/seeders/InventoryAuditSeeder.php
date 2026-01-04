<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventoryAudit;

class InventoryAuditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventoryAudits = [
            [
                'audit_id' => 'AUD-2025-0001',
                'warehouse_name' => 'Main Warehouse',
                'warehouse_location' => 'Mumbai',
                'auditor_name' => 'Rajesh Kumar',
                'auditor_designation' => 'Senior Auditor',
                'status' => 'completed',
                'start_date' => now()->subDays(5),
                'end_date' => now()->subDays(1),
                'items_audited' => 245,
                'remarks' => 'Audit completed successfully with minor discrepancies',
            ],
            [
                'audit_id' => 'AUD-2025-0002',
                'warehouse_name' => 'Secondary Warehouse',
                'warehouse_location' => 'Delhi',
                'auditor_name' => 'Priya Sharma',
                'auditor_designation' => 'Auditor',
                'status' => 'in_progress',
                'start_date' => now()->subDays(3),
                'end_date' => null,
                'items_audited' => 180,
                'remarks' => 'Audit in progress, 50% completed',
            ],
            [
                'audit_id' => 'AUD-2025-0003',
                'warehouse_name' => 'Main Warehouse',
                'warehouse_location' => 'Mumbai',
                'auditor_name' => 'John Doe',
                'auditor_designation' => 'Quality Auditor',
                'status' => 'pending',
                'start_date' => now()->addDays(1),
                'end_date' => null,
                'items_audited' => 0,
                'remarks' => 'Scheduled for next week',
            ],
            [
                'audit_id' => 'AUD-2025-0004',
                'warehouse_name' => 'Secondary Warehouse',
                'warehouse_location' => 'Delhi',
                'auditor_name' => 'Jane Smith',
                'auditor_designation' => 'Senior Auditor',
                'status' => 'completed',
                'start_date' => now()->subDays(10),
                'end_date' => now()->subDays(7),
                'items_audited' => 320,
                'remarks' => 'Audit completed with major discrepancy found',
            ],
        ];

        foreach ($inventoryAudits as $audit) {
            InventoryAudit::create($audit);
        }
    }
}