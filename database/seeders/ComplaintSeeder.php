<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Complaint;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $complaints = [
            [
                'complaint_number' => 'COMP-2025-0001',
                'customer_name' => 'ABC Industries',
                'customer_email' => 'contact@abcindustries.com',
                'customer_phone' => '+91-9876543210',
                'project_name' => 'Solar Farm Project',
                'complaint_type' => 'Technical Issue',
                'description' => 'Solar panels not generating expected power output',
                'priority' => 'high',
                'status' => 'open',
                'assigned_to' => 'Rajesh Kumar',
                'reported_date' => now()->subDays(2),
            ],
            [
                'complaint_number' => 'COMP-2025-0002',
                'customer_name' => 'XYZ Corporation',
                'customer_email' => 'contact@xyzcorp.com',
                'customer_phone' => '+91-9876543211',
                'project_name' => 'Residential Solar',
                'complaint_type' => 'Maintenance',
                'description' => 'Inverter showing error code E001',
                'priority' => 'medium',
                'status' => 'in_progress',
                'assigned_to' => 'Priya Sharma',
                'reported_date' => now()->subDays(1),
            ],
            [
                'complaint_number' => 'COMP-2025-0003',
                'customer_name' => 'DEF Enterprises',
                'customer_email' => 'contact@defenterprises.com',
                'customer_phone' => '+91-9876543212',
                'project_name' => 'Commercial Solar',
                'complaint_type' => 'Billing',
                'description' => 'Incorrect billing for maintenance services',
                'priority' => 'low',
                'status' => 'resolved',
                'assigned_to' => 'John Doe',
                'reported_date' => now()->subDays(5),
                'resolved_date' => now()->subDays(1),
                'resolution_notes' => 'Billing corrected and refund processed',
            ],
        ];

        foreach ($complaints as $complaint) {
            Complaint::create($complaint);
        }
    }
}