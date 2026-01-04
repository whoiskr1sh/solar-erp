<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AMC;

class AMCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amcs = [
            [
                'amc_number' => 'AMC-2025-0001',
                'customer_name' => 'ABC Industries',
                'customer_email' => 'contact@abcindustries.com',
                'project_name' => 'Solar Farm Project',
                'project_location' => 'Mumbai, Maharashtra',
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
                'contract_value' => 500000.00,
                'status' => 'active',
                'services_included' => 'Regular maintenance, cleaning, monitoring, repairs',
                'contact_person' => 'Rajesh Kumar',
                'contact_phone' => '+91-9876543210',
            ],
            [
                'amc_number' => 'AMC-2025-0002',
                'customer_name' => 'XYZ Corporation',
                'customer_email' => 'contact@xyzcorp.com',
                'project_name' => 'Residential Solar',
                'project_location' => 'Delhi, NCR',
                'start_date' => now()->subMonths(3),
                'end_date' => now()->addMonths(9),
                'contract_value' => 250000.00,
                'status' => 'active',
                'services_included' => 'Quarterly maintenance, emergency repairs',
                'contact_person' => 'Priya Sharma',
                'contact_phone' => '+91-9876543211',
            ],
            [
                'amc_number' => 'AMC-2025-0003',
                'customer_name' => 'DEF Enterprises',
                'customer_email' => 'contact@defenterprises.com',
                'project_name' => 'Commercial Solar',
                'project_location' => 'Bangalore, Karnataka',
                'start_date' => now()->subYear(),
                'end_date' => now()->subMonths(2),
                'contract_value' => 750000.00,
                'status' => 'expired',
                'services_included' => 'Annual maintenance, monitoring, repairs',
                'contact_person' => 'John Doe',
                'contact_phone' => '+91-9876543212',
            ],
        ];

        foreach ($amcs as $amc) {
            AMC::create($amc);
        }
    }
}