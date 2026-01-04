<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QualityCheck;

class QualityCheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing quality checks to prevent duplicates
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('quality_checks')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $qualityChecks = [
            [
                'qc_number' => 'QC-2025-0001',
                'item_name' => 'Solar Panel 320W Monocrystalline',
                'item_code' => 'SP-320-MC-001',
                'vendor_name' => 'SolarTech Solutions',
                'inspector_name' => 'John Doe',
                'inspector_designation' => 'Quality Inspector',
                'status' => 'passed',
                'qc_date' => now()->subDays(1),
                'remarks' => 'All panels passed quality standards',
            ],
            [
                'qc_number' => 'QC-2025-0002',
                'item_name' => 'String Inverter 5KW',
                'item_code' => 'INV-5K-SI-001',
                'vendor_name' => 'PowerMax Services',
                'inspector_name' => 'Jane Smith',
                'inspector_designation' => 'Senior Inspector',
                'status' => 'failed',
                'qc_date' => now()->subDays(2),
                'remarks' => 'Voltage fluctuation detected in 2 units',
            ],
            [
                'qc_number' => 'QC-2025-0003',
                'item_name' => 'Ground Mount Structure',
                'item_code' => 'MS-GM-001',
                'vendor_name' => 'MetalWorks Ltd',
                'inspector_name' => 'Rajesh Kumar',
                'inspector_designation' => 'Quality Inspector',
                'status' => 'pending',
                'qc_date' => now()->subDays(3),
                'remarks' => 'Awaiting material testing results',
            ],
            [
                'qc_number' => 'QC-2025-0004',
                'item_name' => 'DC Cable 4mm²',
                'item_code' => 'CAB-DC-4MM-001',
                'vendor_name' => 'CableCorp Industries',
                'inspector_name' => 'Priya Sharma',
                'inspector_designation' => 'Quality Inspector',
                'status' => 'passed',
                'qc_date' => now()->subDays(4),
                'remarks' => 'Cable specifications meet requirements',
            ],
        ];

        foreach ($qualityChecks as $check) {
            QualityCheck::create($check);
        }
    }
}